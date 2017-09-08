<?php

use yii\db\Migration;

class m170908_104527_init extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shop_discount}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sum' => $this->string(10)->notNull(),
            'start_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'roles' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
                ], $tableOptions);

        
        $this->createTable('{{%shop_discount_category}}', [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);



        $this->createTable('{{%shop_discount_manufacturer}}', [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'manufacturer_id' => $this->integer(),
        ]);
        
        
        $this->createIndex('switch', '{{%shop_discount}}', 'switch', 0);
        $this->createIndex('start_date', '{{%shop_discount}}', 'start_date', 0);
        $this->createIndex('end_date', '{{%shop_discount}}', 'end_date', 0);
        
        $this->createIndex('discount_id', '{{%shop_discount_category}}', 'discount_id', 0);
        $this->createIndex('category_id', '{{%shop_discount_category}}', 'category_id', 0);
        
        $this->createIndex('discount_id', '{{%shop_discount_manufacturer}}', 'discount_id', 0);
        $this->createIndex('manufacturer_id', '{{%shop_discount_manufacturer}}', 'manufacturer_id', 0);
        
        
    }

    public function down() {
        $this->dropTable('{{%shop_discount}}');
        $this->dropTable('{{%shop_discount_category}}');
        $this->dropTable('{{%shop_discount_manufacturer}}');

    }

}
