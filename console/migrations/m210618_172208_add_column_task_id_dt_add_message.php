<?php

use yii\db\Migration;

/**
 * Class m210618_172208_add_column_task_id_dt_add_message
 */
class m210618_172208_add_column_task_id_dt_add_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'task_id', $this->integer()->notNull());
        $this->addColumn('message', 'dt_add', $this->dateTime()->notNull()->defaultExpression('now()'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'task_id');
        $this->dropColumn('message', 'dt_add');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210618_172208_add_column_task_id_dt_add_message cannot be reverted.\n";

        return false;
    }
    */
}
