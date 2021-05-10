<?php
    
    use yii\db\Migration;
    
    /**
     * Class m210501_204325_add_column_status_reply
     */
    class m210501_204325_add_column_status_reply extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->addColumn('replies', 'status', $this->tinyInteger()->defaultValue(1));
        }
        
        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->dropColumn('replies', 'status');
        }
        
        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {
    
        }
    
        public function down()
        {
            echo "m210501_204325_add_column_status_reply cannot be reverted.\n";
    
            return false;
        }
        */
    }
