<?php

use yii\db\Migration;

/**
 * Class m201113_184635_alterColumnLatLong
 */
class m201113_184635_alterColumnLatLong extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('city','lat', $this->string(45)->notNull());
        $this->alterColumn('city','long', $this->string(45)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('city','lat', $this->double()->notNull());
        $this->alterColumn('city','long', $this->double()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201113_184635_alterColumnLatLong cannot be reverted.\n";

        return false;
    }
    */
}
