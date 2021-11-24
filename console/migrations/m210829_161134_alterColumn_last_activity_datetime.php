<?php

    use yii\db\Migration;

    /**
     * Class m210829_161134_alterColumn_last_activity_datetime
     */
    class m210829_161134_alterColumn_last_activity_datetime extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->alterColumn(
                'user',
                'last_activity_datetime',
                $this->timestamp()->notNull()->defaultExpression('now()')
            );
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->alterColumn(
                'user',
                'last_activity_datetime',
                $this->timestamp()->null()
            );
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m210829_161134_alterColumn_last_activity_datetime cannot be reverted.\n";

            return false;
        }
        */
    }
