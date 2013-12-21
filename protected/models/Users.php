<?php
/**
 * 以下是表 'users' 可用的属性:
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property integer $superuser
 * @property integer $status
 * @property string $create_at
 * @property string $lastvisit_at
 */
class Users extends CActiveRecord
{
	/**
	 * @返回字符串相关的数据库表名
	 */
	public function tableName()
	{
		return 'users';
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
			array('create_at', 'required'),
			array('superuser, status', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password, email, activkey', 'length', 'max'=>128),
			array('lastvisit_at', 'safe'),
			array('id, username, password, email, activkey, superuser, status, create_at, lastvisit_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @返回数组定制属性标签(名称= >标签)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'activkey' => 'Activkey',
			'superuser' => 'Superuser',
			'status' => 'Status',
			'create_at' => 'Create At',
			'lastvisit_at' => 'Lastvisit At',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('activkey',$this->activkey,true);
		$criteria->compare('superuser',$this->superuser);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('lastvisit_at',$this->lastvisit_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			//'sort'=>array(
			//	'defaultOrder' => 'id DESC',  	//默认排序
			//),
		));
	}

	/**
	 * @参数的字符串$ className active record类名
	 * @返回 Users 静态模型类
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
