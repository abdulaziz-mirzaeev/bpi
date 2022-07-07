<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class m220707_041254_create_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->char(64)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary()
        ]);

        $this->addPrimaryKey('pk-id', '{{%session}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%session}}');
    }
}
