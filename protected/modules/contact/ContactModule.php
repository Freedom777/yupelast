<?php

use yupe\components\WebModule;

class ContactModule extends WebModule
{
    public function getDependencies()
    {
        return array(
            'user',
        );
    }

    // название модуля
    public function getName()
    {
        return Yii::t('ContactModule.contact', 'Contact');
    }

    // описание модуля
    public function getDescription()
    {
        return Yii::t('ContactModule.contact', 'Module for managing user contacts');
    }

    // автор модуля (Ваше Имя, название студии и т.п.)
    public function getAuthor()
    {
        return Yii::t('ContactModule.contact', 'Freedom');
    }

    // контактный email автора
    public function getAuthorEmail()
    {
        return Yii::t('ContactModule.contact', 'oleg_freedom@rambler.ru');
    }

    // сайт автора или страничка модуля
    public function getUrl()
    {
        return Yii::t('ContactModule.contact', 'https://github.com/oleg_freedom');
    }

    // категория модуля
    public function getCategory()
    {
        return Yii::t('ContactModule.contact', 'Users');
    }

    public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'contact.models.*',
			'contact.components.*',
            'contact.messages.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

}
