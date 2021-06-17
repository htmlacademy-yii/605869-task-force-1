<?php

use yii\db\Migration;

/**
 * Class m210615_194218_alterColumn_address
 */
class m210615_194218_alterColumn_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task', 'address', $this->string(200));
        $this->alterColumn('profiles', 'address', $this->string(200));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('task', 'address', $this->string(45));
        $this->alterColumn('profiles', 'address', $this->string(45));

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210615_194218_alterColumn_address cannot be reverted.\n";

        return false;
    }
    */
}
