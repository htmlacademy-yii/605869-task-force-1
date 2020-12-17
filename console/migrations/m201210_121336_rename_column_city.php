<?php

    use yii\db\Migration;

    /**
     * Class m201210_121336_rename_column_city
     */
    class m201210_121336_rename_column_city extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->renameColumn('city', 'city', 'name');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->renameColumn('city','name', 'city');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m201210_121336_rename_column_city cannot be reverted.\n";

            return false;
        }
        */
    }
