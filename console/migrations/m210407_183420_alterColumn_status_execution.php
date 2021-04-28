<?php
    
    use yii\db\Migration;
    
    /**
     * Class m210407_183420_alterColumn_status_execution
     */
    class m210407_183420_alterColumn_status_execution extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->alterColumn('task', 'status_execution', $this->integer()->defaultValue(1));
        }
        
        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->alterColumn('task', 'status_execution', $this->integer()->defaultValue(1));

        }
        
        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {
    
        }
    
        public function down()
        {
            echo "m210407_183420_alterColumn_status_execution cannot be reverted.\n";
    
            return false;
        }
        */
    }
