<?php

    use yii\db\Migration;

    /**
     * Class m210829_165702_rename_column_email
     */
    class m210829_165702_rename_column_email extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->renameColumn('profiles', 'email', 'telegram');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->renameColumn('profiles', 'telegram', 'email');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m210829_165702_rename_column_email cannot be reverted.\n";

            return false;
        }
        */
    }
