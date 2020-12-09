<?php

    use yii\db\Migration;

    /**
     * Class m201113_183617_alterColumnLatLong
     */
    class m201113_183617_alterColumnLatLong extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->alterColumn('task','lat', $this->string(45)->notNull());
            $this->alterColumn('task','long', $this->string(45)->notNull());
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->alterColumn('task','lat', $this->double()->notNull());
            $this->alterColumn('task','long', $this->double()->notNull());
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m201113_183617_alterColumnLatLong cannot be reverted.\n";

            return false;
        }
        */
    }
