<?php

class m141208_120629_user_add_age_fields extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('{{user_user}}', 'meeting_date', 'datetime NOT NULL AFTER `birth_date`');
        $this->addColumn('{{user_user}}', 'age', 'tinyint UNSIGNED NULL DEFAULT NULL AFTER `meeting_date`');
	}

	public function safeDown()
	{
        $this->dropColumn('{{user_user}}', 'meeting_date');
        $this->dropColumn('{{user_user}}', 'age');
	}
}