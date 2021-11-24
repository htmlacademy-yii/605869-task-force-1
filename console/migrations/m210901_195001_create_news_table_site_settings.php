<?php

    use yii\db\Migration;

    /**
     * Class m210901_195001_create_news_table_site_settings
     */
    class m210901_195001_create_news_table_site_settings extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->createTable('site_settings', [
                'id' => $this->primaryKey()->notNull(),
                'user_id' => $this->integer()->notNull(),
                'show_new_messages' => $this->tinyInteger()->notNull()->defaultValue('1'),
                'show_actions_of_task' => $this->tinyInteger()->notNull()->defaultValue('1'),
                'show_new_review' => $this->tinyInteger()->notNull()->defaultValue('1'),
                'show_my_contacts_customer' => $this->tinyInteger()->notNull()->defaultValue('1'),
                'hide_account' => $this->tinyInteger()->notNull()->defaultValue('1'),
            ],                 'charset=utf8');

            // добавление индексов
            $this->createIndex('site_settings_user_idx', 'site_settings', 'user_id');

            // добавление ключей
            $this->addForeignKey('fk_site_settings_user_id', 'site_settings', 'user_id', 'user', 'id');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            // удаление индексов
            $this->dropIndex('site_settings_user_idx', 'site_settings');

            // удаление ключей
            $this->dropForeignKey('fk_site_settings_user_id', 'site_settings');

            $this->dropTable('site_settings');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m210901_195001_create_news_table_site_settings cannot be reverted.\n";

            return false;
        }
        */
    }
