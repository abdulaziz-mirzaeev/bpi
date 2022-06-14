<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accounts}}`.
 */
class m220529_001804_create_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accounts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'display_name' => $this->string(255)->null(),
            'visible' => $this->tinyInteger()->defaultValue(\app\models\Account::VISIBLE_TRUE),
            'type' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%accounts}}');
    }
}
