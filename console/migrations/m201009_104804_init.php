<?php

use yii\db\Migration;

/**
 * Class m201009_104804_init
 */
class m201009_104804_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //создание таблиц
        $this->createTable('user', [
            'id' => $this->primaryKey()->notNull(),
            'name' => $this->string(45)->notNull(),
            'email' => $this->string(45)->notNull(),
            'password' => $this->string(64)->notNull(),
            'dt_add' => $this->dateTime()->notNull()->defaultExpression('now()')
        ], 'charset=utf8');

        $this->createTable('category', [
            'id' => $this->primaryKey()->notNull(),
            'name' => $this->string(45)->notNull(),
            'icon' => $this->string(45)->notNull()
        ], 'charset=utf8');

        $this->createTable('city', [
            'id' => $this->primaryKey()->notNull(),
            'city' => $this->string(45)->notNull(),
            'lat' => $this->double()->notNull(),
            'long' => $this->double()->notNull(),
        ], 'charset=utf8');

        $this->createTable('status', [
            'id' => $this->primaryKey(),
            'name' => $this->string(45)->notNull(),
            'translate' => $this->string(45)->notNull(),
        ], 'charset=utf8');

        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'name' => $this->string(45)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
            'address' => $this->string(45),
            'budget' => $this->decimal(10,2)->notNull(),
            'expire' => $this->dateTime()->notNull(),
            'description' => $this->text()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'status_execution' => $this->tinyInteger(1)->notNull(),
            'replies_id' => $this->integer(),
            'lat' => $this->double()->notNull(),
            'long' => $this->double()->notNull(),
            'executor_id' => $this->integer(),
            'dt_add' => $this->dateTime()->notNull()->defaultExpression('now()')
        ], 'charset=utf8');

        $this->createTable('file', [
            'id' => $this->primaryKey()->notNull(),
            'name' => $this->string(128)->notNull(),
            'task_id' => $this->integer()->notNull()
        ], 'charset=utf8');

        $this->createTable('replies', [
            'id' => $this->primaryKey()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'description' => $this->text()->notNull(),
            'dt_add' => $this->dateTime()->notNull()->defaultExpression('now()')
        ], 'charset=utf8');

        $this->createTable('message', [
            'id' => $this->primaryKey()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull()
        ], 'charset=utf8');

        $this->createTable('photo', [
            'id' => $this->primaryKey()->notNull(),
            'name' => $this->string(128)->notNull(),
            'user_id' => $this->integer()->notNull()
        ], 'charset=utf8');

        $this->createTable('opinions', [
            'id' => $this->primaryKey()->notNull(),
            'dt_add' => $this->dateTime()->null()->defaultExpression('now()'),
            'rate' => $this->integer()->null(),
            'task_id' => $this->integer()->notNull(),
            'comment' => $this->text()->null()
        ], 'charset=utf8');

        $this->createTable('specialization', [
            'id' => $this->primaryKey()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()
        ], 'charset=utf8');

        $this->createTable('profiles', [
            'id' => $this->primaryKey()->notNull(),
            'address' => $this->string(45)->null(),
            'bd' => $this->date()->null(),
            'about' => $this->text()->null(),
            'phone' => $this->string(45)->null(),
            'telegram' => $this->string(45)->null(),
            'counter_failed_tasks' => $this->tinyInteger()->null(),
            'specialization_id' => $this->integer(),
            'city_id' => $this->integer()->notNull(),
            'avatar' => $this->string(45)->null(),
            'user_id' => $this->integer()->notNull()
        ], 'charset=utf8');

        // добавление индексов
        $this->createIndex('user_name_idx', 'user', 'name');
        $this->createIndex('category_name_idx', 'category', 'name');
        $this->createIndex('city_city_idx', 'city', 'city');
        $this->createIndex('status_name_idx', 'status', 'name');
        $this->createIndex('task_catagory_idx', 'task', 'category_id');
        $this->createIndex('task_customer_idx', 'task', 'customer_id');
        $this->createIndex('task_city_idx', 'task', 'city_id');
        $this->createIndex('task_executor_idx', 'task', 'executor_id');
        $this->createIndex('task_status_idx', 'task', 'status_id');
        $this->createIndex('file_task_idx', 'file', 'task_id');
        $this->createIndex('replies_task_idx', 'replies', 'task_id');
        $this->createIndex('replies_user_idx', 'replies', 'user_id');
        $this->createIndex('message_recipient_idx', 'message', 'recipient_id');
        $this->createIndex('message_sender_idx', 'message', 'sender_id');
        $this->createIndex('photo_user_idx', 'photo', 'user_id');
        $this->createIndex('specialization_user_idx', 'specialization', 'user_id');
        $this->createIndex('specialization_category_idx', 'specialization', 'category_id');
        $this->createIndex('profiles_city_idx', 'profiles', 'city_id');
        $this->createIndex('profiles_user_idx', 'profiles', 'user_id');
        $this->createIndex('profiles_specialization_idx', 'profiles', 'specialization_id');

        // добавление ключей
        $this->addForeignKey('fk_task_catagory', 'task', 'category_id', 'category', 'id');
        $this->addForeignKey('fk_task_customer', 'task', 'customer_id', 'user', 'id');
        $this->addForeignKey('fk_task_city', 'task', 'city_id', 'city', 'id');
        $this->addForeignKey('fk_task_executor', 'task', 'executor_id', 'user', 'id');
        $this->addForeignKey('fk_task_status', 'task', 'status_id', 'status', 'id');
        $this->addForeignKey('fk_file_task', 'file', 'task_id', 'task', 'id');
        $this->addForeignKey('fk_response_task', 'replies', 'task_id', 'task', 'id');
        $this->addForeignKey('fk_response_user', 'replies', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_message_recipient', 'message', 'recipient_id', 'user', 'id');
        $this->addForeignKey('fk_message_sender', 'message', 'sender_id', 'user', 'id');
        $this->addForeignKey('fk_photo_user', 'photo', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_opinions_task', 'opinions', 'task_id', 'task', 'id');
        $this->addForeignKey('fk_specialization_user', 'specialization', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_specialization_category', 'specialization', 'category_id', 'category', 'id');
        $this->addForeignKey('fk_profiles_city', 'profiles', 'city_id', 'city', 'id');
        $this->addForeignKey('fk_profiles_user', 'profiles', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_profiles_specializ', 'profiles', 'specialization_id', 'specialization', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // удаление индексов
        $this->dropIndex('user_name_idx', 'user');
        $this->dropIndex('category_name_idx', 'category');
        $this->dropIndex('city_city_idx', 'city');
        $this->dropIndex('status_name_idx', 'status');
        $this->dropIndex('task_catagory_idx', 'task');
        $this->dropIndex('task_customer_idx', 'task');
        $this->dropIndex('task_city_idx', 'task');
        $this->dropIndex('task_executor_idx', 'task');
        $this->dropIndex('task_status_idx', 'task');
        $this->dropIndex('file_task_idx', 'file');
        $this->dropIndex('replies_task_idx', 'replies');
        $this->dropIndex('replies_user_idx', 'replies');
        $this->dropIndex('message_recipient_idx', 'message');
        $this->dropIndex('message_sender_idx', 'message');
        $this->dropIndex('photo_user_idx', 'photo');
        $this->dropIndex('specialization_user_idx', 'specialization');
        $this->dropIndex('specialization_category_idx', 'specialization');
        $this->dropIndex('profiles_city_idx', 'profiles');
        $this->dropIndex('profiles_user_idx', 'profiles');
        $this->dropIndex('profiles_specialization_idx', 'profiles');

        // удаление ключей
        $this->dropForeignKey('fk_task_catagory', 'task');
        $this->dropForeignKey('fk_task_customer', 'task');
        $this->dropForeignKey('fk_task_city', 'task');
        $this->dropForeignKey('fk_task_executor', 'task');
        $this->dropForeignKey('fk_task_status', 'task');
        $this->dropForeignKey('fk_file_task', 'file');
        $this->dropForeignKey('fk_response_task', 'replies');
        $this->dropForeignKey('fk_response_user', 'replies');
        $this->dropForeignKey('fk_message_recipient', 'message');
        $this->dropForeignKey('fk_message_sender', 'message');
        $this->dropForeignKey('fk_photo_user', 'photo');
        $this->dropForeignKey('fk_opinions_task', 'opinions');
        $this->dropForeignKey('fk_specialization_user', 'specialization');
        $this->dropForeignKey('fk_specialization_category', 'specialization');
        $this->dropForeignKey('fk_profiles_city', 'profiles');
        $this->dropForeignKey('fk_profiles_user', 'profiles');
        $this->dropForeignKey('fk_profiles_specializ', 'profiles');

        // удаление таблиц
        $this->dropTable('profiles');
        $this->dropTable('specialization');
        $this->dropTable('opinions');
        $this->dropTable('photo');
        $this->dropTable('message');
        $this->dropTable('replies');
        $this->dropTable('file');
        $this->dropTable('task');
        $this->dropTable('status');
        $this->dropTable('city');
        $this->dropTable('category');
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201009_104804_init cannot be reverted.\n";

        return false;
    }
    */
}
