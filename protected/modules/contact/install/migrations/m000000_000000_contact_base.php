<?php

/**
 * FileDocComment
 * Category install migration
 * Класс миграций для модуля Category:
 *
 * @category YupeMigration
 * @package  yupe.modules.category.install.migrations
 * @author   YupeTeam <team@yupe.ru>
 * @license  BSD https://raw.github.com/yupe/yupe/master/LICENSE
 * @link     http://yupe.ru
 **/
class m000000_000000_category_base extends yupe\components\DbMigration
{
    /**
     * Функция настройки и создания таблицы:
     *
     * @return null
     **/
    public function safeUp()
    {
        $this->createTable(
            '{{contact_provider}}',
            array(
                'id'                => 'pk',
                'code'              => 'varchar(255) NOT NULL',
                'name'              => 'varchar(255) NOT NULL',
                'template'          => 'varchar(255) NOT NULL',
                'icon'              => 'varchar(255) NOT NULL',
                'fa_css_class'      => 'varchar(255) NOT NULL',
            ),
            $this->getOptions()
        );

        //ix
        $this->createIndex("ux_{{contact_provider}}_code", '{{contact_provider}}', "code", true);

        $this->createTable(
            '{{contact_user}}',
            array(
                'id'                => 'pk',
                'user_id'           => 'integer DEFAULT NULL',
                'provider_id'       => 'integer DEFAULT NULL',
                'uid'               => 'varchar(255) NOT NULL',
                'description'       => 'varchar(255) NOT NULL',
            ),
            $this->getOptions()
        );

        //fk
        $this->addForeignKey(
            "fk_{{contact_user}}_user_id",
            '{{contact_user}}',
            'user_id',
            '{{user_user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            "fk_{{contact_user}}_provider_id",
            '{{contact_user}}',
            'provider_id',
            '{{contact_provider}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Contact Provider start data
        $fieldsAr = array('code', 'name', 'template', 'icon', 'fa_css_class');

        $rowsAr = array(
            array('phone', 'Телефон', '', '', 'fa-phone'),
            array('skype', 'Скайп', 'skype:{%uid%}?chat', '', 'fa-skype'),
            array('vk', 'Вконтакте', 'http://vk.com/{%uid%}', '', 'fa-vk'),
            array('od', 'Одноклассники', 'http://ok.ru/profile/{%uid%}', '', 'fa-angellist'),
            array('address', 'Адрес', '', '', 'fa-home'),
            array('love.mail.ru', 'Лаврамблер', 'http://love.mail.ru/mb{%uid%}', '', 'fa-heart'),
            array('loveplanet.ru', 'Лавпленет', 'http://loveplanet.ru/page/{%uid%}', '', 'fa-heart'),
            array('email', 'Email', 'mailto:{%uid%}', '', 'fa-envelope')
        );

        foreach ( $rowsAr as $rowAr) {

            $itemAr = array_combine($fieldsAr, $rowAr);

            $this->insert(
                '{{contact_provider}}',
                $itemAr
            );
        }

    }

    /**
     * Функция удаления таблицы:
     *
     * @return null
     **/
    public function safeDown()
    {
        $this->dropTableWithForeignKeys('{{contact_user}}');
        $this->dropTableWithForeignKeys('{{contact_provider}}');
    }
}
