<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%records}}`.
 */
class m220605_110640_create_records_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%records}}', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer(),
            'value' => $this->decimal(15, 2),
            'date' => $this->date(),
            'type' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%records}}');
    }
}
