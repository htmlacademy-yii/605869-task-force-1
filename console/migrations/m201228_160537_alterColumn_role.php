<?php

use yii\db\Migration;

/**
 * Class m201228_160537_alterColumn_role
 */
class m201228_160537_alterColumn_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	 $this->alterColumn( 'user', 'role', $this->tinyInteger()->defaultValue(2));
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->alterColumn( 'user', 'role', $this->tinyInteger()->notNull());
	}

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201228_160537_alterColumn_role cannot be reverted.\n";

        return false;
    }
    */
}
