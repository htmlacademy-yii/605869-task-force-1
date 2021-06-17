<?php

use yii\db\Migration;

/**
 * Class m210615_170728_add_column_kladr_city
 */
class m210615_170728_add_column_kladr_city extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('city', 'kladr', $this->string(13));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('city', 'kladr');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210615_170728_add_column_kladr_city cannot be reverted.\n";

        return false;
    }
    */
}
