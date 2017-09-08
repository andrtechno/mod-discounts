<?php

use yii\db\Migration;

class m170908_104527_discounts extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%discount}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sum' => $this->string(10)->notNull(),
            'start_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'roles' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
                ], $tableOptions);


        $this->createTable('{{%discount_category}}', [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'category_id' => $this->integer(),
        ], $tableOptions);



        $this->createTable('{{%discount_manufacturer}}', [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'manufacturer_id' => $this->integer(),
        ], $tableOptions);


        $this->createIndex('switch', '{{%discount}}', 'switch', 0);
        $this->createIndex('start_date', '{{%discount}}', 'start_date', 0);
        $this->createIndex('end_date', '{{%discount}}', 'end_date', 0);

        $this->createIndex('discount_id', '{{%discount_category}}', 'discount_id', 0);
        $this->createIndex('category_id', '{{%discount_category}}', 'category_id', 0);

        $this->createIndex('discount_id', '{{%discount_manufacturer}}', 'discount_id', 0);
        $this->createIndex('manufacturer_id', '{{%discount_manufacturer}}', 'manufacturer_id', 0);
    }

    public function down() {
        $this->dropTable('{{%discount}}');
        $this->dropTable('{{%discount_category}}');
        $this->dropTable('{{%discount_manufacturer}}');
    }

}
