<?php

use yii\db\Migration;

/**
 * Class m220122_141342_alterColumn_name_in_task
 */
class m220122_141342_alterColumn_name_in_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task','name', $this->string(128)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('task','name', $this->string(45)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220122_141342_alterColumn_name_in_task cannot be reverted.\n";

        return false;
    }
    */
}
