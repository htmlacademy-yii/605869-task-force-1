<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorite}}`.
 */
class m201129_201413_create_favorite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('favorite_user', [
            'id' => $this->primaryKey()->notNull(),
            'authorized_id' => $this->integer()->notNull(),
            'favorite_user_id' => $this->integer()->notNull()
        ], 'charset=utf8');

        $this->createIndex('favorite_authorized_idx', 'favorite_user', 'authorized_id');
        $this->createIndex('favorite_user_idx', 'favorite_user', 'favorite_user_id');

        $this->addForeignKey('fk_favorite_authorized', 'favorite_user', 'authorized_id', 'user', 'id');
        $this->addForeignKey('fk_favorite_user', 'favorite_user', 'favorite_user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('favorite_authorized_idx', 'favorite_user');
        $this->dropIndex('favorite_user_idx', 'favorite_user');

        $this->dropForeignKey('fk_favorite_authorized', 'favorite_user');
        $this->dropForeignKey('fk_favorite_user', 'favorite_user');

        $this->dropTable('favorite_user');
    }
}
