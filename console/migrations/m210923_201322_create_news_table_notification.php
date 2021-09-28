<?php

    use yii\db\Migration;

    /**
     * Class m210923_201322_create_news_table_notification
     */
    class m210923_201322_create_news_table_notification extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->createTable('notification', [
                'id' => $this->primaryKey()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'task_id' => $this->integer()->notNull(),
                'is_view' => $this->tinyInteger()->notNull()->defaultValue('1'),
                'title' => $this->string(128)->null(),
                'icon' => $this->string(128)->null(),
                'description' => $this->string(128)->null(),
                'dt_add' => $this->dateTime()->notNull()->defaultExpression('now()'),
            ]);

            // добавление индексов
            $this->createIndex('notification_user_idx', 'notification', 'user_id');
            $this->createIndex('notification_task_idx', 'notification', 'task_id');

            // добавление ключей
            $this->addForeignKey('fk_notification_user_id', 'notification', 'user_id', 'user', 'id');
            $this->addForeignKey('fk_notification_task_id', 'notification', 'task_id', 'task', 'id');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            // удаление индексов
            $this->dropIndex('notification_user_idx', 'notification');
            $this->dropIndex('notification_task_idx', 'notification');

            // удаление ключей
            $this->dropForeignKey('fk_notification_user_id', 'notification');
            $this->dropForeignKey('fk_notification_task_id', 'notification');

            $this->dropTable('notification');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m210923_201322_create_news_table_notification cannot be reverted.\n";

            return false;
        }
        */
    }
