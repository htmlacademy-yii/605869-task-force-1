<?php

use yii\db\Migration;

/**
 * Class m210615_193343_alterColumn_kladr
 */
class m210615_193343_alterColumn_kladr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('city', 'kladr', $this->string(19));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('city', 'kladr', $this->string(13));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210615_193343_alterColumn_kladr cannot be reverted.\n";

        return false;
    }
    */
}
