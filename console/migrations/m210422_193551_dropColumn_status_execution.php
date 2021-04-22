<?php
    
    use yii\db\Migration;
    
    /**
     * Class m210422_193551_dropColumn_status_execution
     */
    class m210422_193551_dropColumn_status_execution extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->dropColumn('task', 'status_execution');
        }
        
        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->addColumn('task', 'status_execution', $this->integer()->defaultValue(1));
        }
        
        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {
    
        }
    
        public function down()
        {
            echo "m210422_193551_dropColumn_status_execution cannot be reverted.\n";
    
            return false;
        }
        */
    }
