<?php

/**
 * This is the model class for table "{{contact_user}}".
 *
 * The followings are the available columns in table '{{contact_user}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $provider_id
 * @property string $uid
 * @property string $description
 *
 * The followings are the available model relations:
 * @property UserUser $user
 * @property ContactProvider $provider
 */
class ContactUser extends yupe\models\YModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{contact_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, provider_id, uid', 'required'),
			array('user_id, provider_id', 'numerical', 'integerOnly'=>true),
			array('uid, description', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, provider_id, uid, description', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'UserUser', 'user_id'),
			'provider' => array(self::BELONGS_TO, 'ContactProvider', 'provider_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            // 'id'                => Yii::t('CategoryModule.category', 'Id'),
            /*
			'id' => Yii::t('ContactModule.contactuser', 'ID'),
			'user_id' => Yii::t('ContactModule.contactuser', 'User'),
			'provider_id' => Yii::t('ContactModule.contactuser', 'Provider'),
			'uid' => Yii::t('ContactModule.contactuser', 'Uid'),
			'description' => Yii::t('ContactModule.contactuser', 'Description'),
            */

            'id' => Yii::t('ContactModule.contact', 'ID'),
            'user_id' => Yii::t('ContactModule.contact', 'User'),
            'provider_id' => Yii::t('ContactModule.contact', 'Provider'),
            'uid' => Yii::t('ContactModule.contact', 'Uid'),
            'description' => Yii::t('ContactModule.contact', 'Description'),
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('provider_id',$this->provider_id);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContactUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getFormConfig()
    {
        return array(
            'elements'=>array(
                'provider_id' => array(
                    'type'=>'dropdownlist',
                    'items' => ContactProvider::getAssocList(),
                ),
                'uid'=>array(
                    'type' => 'text',
                    'maxlength' => 255,
                ),
                'description'=>array(
                    'type' => 'text',
                    'maxlength' => 255,
                ),
            ));
    }
}
