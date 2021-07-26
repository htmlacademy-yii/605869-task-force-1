<?php

use yii\db\Migration;

/**
 * Class m210618_195740_drop_colomn_replies_id_task
 */
class m210618_195740_drop_colomn_replies_id_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('task', 'replies_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('task', 'replies_id', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210618_195740_drop_colomn_replies_id_task cannot be reverted.\n";

        return false;
    }
    */
}
