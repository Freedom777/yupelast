<?php

/**
 * This is the model class for table "{{contact_provider}}".
 *
 * The followings are the available columns in table '{{contact_provider}}':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $template
 * @property string $icon
 * @property string $fa_css_class
 *
 * The followings are the available model relations:
 * @property ContactUser[] $contactUsers
 */
class ContactProvider extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{contact_provider}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name, template, icon, fa_css_class', 'required'),
			array('code, name, template, icon, fa_css_class', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, name, template, icon, fa_css_class', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'contactUsers' => array(self::HAS_MANY, 'ContactUser', 'provider_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'template' => 'Template',
			'icon' => 'Icon',
			'fa_css_class' => 'Fa Css Class',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('fa_css_class',$this->fa_css_class,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactProvider the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
