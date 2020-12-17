<?php

    use yii\db\Migration;

    /**
     * Class m201210_134619_rename_column_telegram
     */
    class m201210_134619_rename_column_telegram extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->renameColumn('profiles', 'telegram', 'email');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->renameColumn('profiles', 'email', 'telegram');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m201210_134619_rename_column_telegram cannot be reverted.\n";

            return false;
        }
        */
    }
