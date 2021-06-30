<?php

use yii\db\Migration;

/**
 * Class m210627_095339_dropColumn_recipient_id_message
 */
class m210627_095339_dropColumn_recipient_id_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_message_recipient', 'message');

        $this->dropIndex('message_recipient_idx', 'message');

        $this->dropColumn('message', 'recipient_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('message', 'recipient_id', $this->integer()->notNull(),);

        $this->createIndex('message_recipient_idx', 'message', 'recipient_id');

        $this->addForeignKey('fk_message_recipient', 'message', 'recipient_id', 'user', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210627_095339_dropColumn_recipient_id_message cannot be reverted.\n";

        return false;
    }
    */
}
