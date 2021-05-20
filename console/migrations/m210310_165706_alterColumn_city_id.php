<?php

use yii\db\Migration;

/**
 * Class m210310_165706_alterColumn_city_id
 */
class m210310_165706_alterColumn_city_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->alterColumn( 'task', 'city_id', $this->integer()->Null());
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn( 'task', 'city_id', $this->integer()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210310_165706_alterColumn_city_id cannot be reverted.\n";

        return false;
    }
    */
}
