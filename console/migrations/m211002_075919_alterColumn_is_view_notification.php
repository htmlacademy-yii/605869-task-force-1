<?php

    use yii\db\Migration;

    /**
     * Class m211002_075919_alterColumn_is_view_notification
     */
    class m211002_075919_alterColumn_is_view_notification extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->alterColumn('notification', 'is_view', $this->tinyInteger()->notNull()->defaultValue(0));
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->alterColumn('notification', 'is_view', $this->tinyInteger()->notNull()->defaultValue(1));
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m211002_075919_alterColumn_is_view_notification cannot be reverted.\n";

            return false;
        }
        */
    }
