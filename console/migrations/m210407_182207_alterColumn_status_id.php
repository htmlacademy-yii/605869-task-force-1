<?php
    
    use yii\db\Migration;
    
    /**
     * Class m210407_182207_alterColumn_status_id
     */
    class m210407_182207_alterColumn_status_id extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->alterColumn('task', 'status_id', $this->tinyInteger(1)->defaultValue(1));
        }
        
        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->alterColumn('task', 'status_id', $this->tinyInteger(1)->notNull());
        }
        
        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {
    
        }
    
        public function down()
        {
            echo "m210407_182207_alterColumn_status_id cannot be reverted.\n";
    
            return false;
        }
        */
    }
