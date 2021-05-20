<?php

use yii\db\Migration;

/**
 * Class m201130_183152_add_column_activity
 */
class m201130_183152_add_column_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','last_activity_datetime', $this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'last_activity_datetime');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201130_183152_add_column_activity cannot be reverted.\n";

        return false;
    }
    */
}
