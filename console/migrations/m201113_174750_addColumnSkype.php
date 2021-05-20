<?php

    use yii\db\Migration;

    /**
     * Class m201113_174750_addColumnSkype
     */
    class m201113_174750_addColumnSkype extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->addColumn('profiles','skype', $this->string(45)->null());
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->dropColumn('profiles', 'skype');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m201113_174750_addColumnSkype cannot be reverted.\n";

            return false;
        }
        */
    }
