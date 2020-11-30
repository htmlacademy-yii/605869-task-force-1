<?php

use yii\db\Migration;

/**
 * Class m201128_145426_insert_content_status
 */
class m201128_145426_insert_content_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('status', ['id' => '2','name' => 'cancel','translate' => 'Отменен']);
        $this->insert('status', ['id' => '3','name' => 'in_work','translate' => 'В работе']);
        $this->insert('status', ['id' => '4','name' => 'performed','translate' => 'Выполнено']);
        $this->insert('status', ['id' => '5','name' => 'failed','translate' => 'Провалено']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('status', ['id' => '2']);
        $this->delete('status', ['id' => '3']);
        $this->delete('status', ['id' => '4']);
        $this->delete('status', ['id' => '5']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201128_145426_insert_content_status cannot be reverted.\n";

        return false;
    }
    */
}
