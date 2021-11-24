<?php

    use yii\db\Migration;

    /**
     * Class m210913_202302_dropColumn_id_specialization
     */
    class m210913_202302_dropColumn_id_specialization extends Migration
    {
        /**
         * {@inheritdoc}
         */
        public function safeUp()
        {
            $this->dropForeignKey('fk_profiles_specializ', 'profiles');
            $this->dropIndex('profiles_specialization_idx', 'profiles');

            $this->dropColumn('specialization', 'id');
            $this->dropColumn('profiles', 'specialization_id');
        }

        /**
         * {@inheritdoc}
         */
        public function safeDown()
        {
            $this->addColumn('specialization', 'id', $this->primaryKey()->notNull());
            $this->addColumn('profiles', 'specialization_id', $this->integer());

            $this->createIndex('profiles_specialization_idx', 'profiles', 'specialization_id');
            $this->addForeignKey('fk_profiles_specializ', 'profiles', 'specialization_id', 'specialization', 'id');
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m210913_202302_dropColumn_id_specialization cannot be reverted.\n";

            return false;
        }
        */
    }
