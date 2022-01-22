<?php

use yii\db\Migration;

/**
 * Class m220117_200916_category
 */
class m220117_200916_category extends Migration
{
    public $table = "{{category}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert($this->table, [
            'name' => 'Переводы',
            'icon' => 'translation',
        ]);
        $this->insert($this->table, [
            'name' => 'Уборка',
            'icon' => 'clean',
        ]);
        $this->insert($this->table, [
            'name' => 'Переезды',
            'icon' => 'cargo',
        ]);
        $this->insert($this->table, [
            'name' => 'Компьютерная помощь',
            'icon' => 'neo',
        ]);
        $this->insert($this->table, [
            'name' => 'Ремонт квартирный',
            'icon' => 'flat',
        ]);
        $this->insert($this->table, [
            'name' => 'Ремонт техники',
            'icon' => 'repair',
        ]);
        $this->insert($this->table, [
            'name' => 'Красота',
            'icon' => 'beauty',
        ]);
        $this->insert($this->table, [
            'name' => 'Фото',
            'icon' => 'photo',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable($this->table);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220117_200916_category cannot be reverted.\n";

        return false;
    }
    */
}
