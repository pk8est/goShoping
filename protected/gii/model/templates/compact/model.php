<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>
/**
 * 以下是表 '<?php echo $tableName; ?>' 可用的属性:
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
	/**
	 * @返回字符串相关的数据库表名
	 */
	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}

	/**
	 * @返回数组模型属性的验证规则
	 * 验证规则：
	 *		required : CRequiredValidator 的别名, 确保了特性不为空.
	 *		unique : CUniqueValidator 的别名, 确保了特性在数据表字段中是唯一的.
	 * 		boolean : CBooleanValidator 的别名,确保属性的值是true或false.
	 *		captcha : CCaptchaValidator 的别名,确保了特性的值等于 CAPTCHA 显示出来的验证码.
	 *		compare : CCompareValidator 的别名, 确保了特性的值等于另一个特性或常量.
	 *		email : CEmailValidator 的别名,确保了特性的值是一个有效的电邮地址.
	 *		default : CDefaultValueValidator 的别名, 为特性指派了一个默认值.
	 *		exist : CExistValidator 的别名, 确保属性值存在于指定的数据表字段中.
	 *		file : CFileValidator 的别名, 确保了特性包含了一个上传文件的名称.
	 *		filter : CFilterValidator 的别名, 使用一个filter转换属性.
	 *		in : CRangeValidator 的别名, 确保了特性出现在一个预订的值列表里.
	 *		length : CStringValidator 的别名, 确保了特性的长度在指定的范围内.
	 *		match : CRegularExpressionValidator 的别名, 确保了特性匹配一个正则表达式.
	 *		numerical : CNumberValidator 的别名, 确保了特性是一个有效的数字.
	 *		type : CTypeValidator 的别名, 确保了特性为指定的数据类型.
	 *		url : CUrlValidator 的别名, 确保了特性是一个有效的路径.
	 */
	public function rules()
	{
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @返回数组的关系规则.
	 * 关系规则(RelationType)：
	 *		BELONGS_TO : A和B的关系是一对多，那么B属于A
	 *		HAS_MANY   : A和B之间的关系是一对多，那么A有多个B
	 *		HAS_ONE    : 这是HAS_MANY的一种特殊情况，A至多有一个B
	 *		MANY_MANY  : 这个对应多对多的情况，在AR里会将多对多以BELONGS_TO和HAS_MANY的组合来解释
	 * 用法：
	 *		'RelationName'=>array('RelationType', 'ClassName', 'ForeignKey' [, 'select'=>'', 
	 *		'condition'=>'', 'order'=>'', 'with'=>'', 'joinType'=>'', 'alias'=>'', 'params'=>'',
	 *		'on'=>'', 'index'=>'', 'scopes'=>'']),
	 */
	public function relations()
	{
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * @返回数组定制属性标签(名称= >标签)
	 */
	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$label',\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * 检索列表模型基于当前搜索/过滤条件
	 * @返回CActiveDataProvider可以返回的数据提供程序模型
	 * 基于搜索/过滤条件
	 */
	public function search()
	{

		$criteria=new CDbCriteria;

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			//'sort'=>array(
			//	'defaultOrder' => 'id DESC',  	//默认排序
			//),
		));
	}

<?php if($connectionId!='db'):?>
	/**
	 * @返回CDbConnection这个类使用的数据库连接
	 */
	public function getDbConnection()
	{
		return Yii::app()-><?php echo $connectionId ?>;
	}

<?php endif?>
	/**
	 * @参数的字符串$ className active record类名
	 * @返回 <?php echo $modelClass; ?> 静态模型类
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
