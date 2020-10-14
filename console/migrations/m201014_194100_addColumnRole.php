<?php

use yii\db\Migration;

/**
 * Class m201014_194100_addColumnRole
 */
class m201014_194100_addColumnRole extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'role', $this->tinyInteger()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'role');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201014_194100_addColumnRole cannot be reverted.\n";

        return false;
    }
    */
}
