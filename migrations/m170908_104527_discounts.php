<?php

namespace panix\mod\discounts\migrations;

/**
 * Generation migrate by PIXELION CMS
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * Class m170908_104527_discounts
 */

use panix\engine\db\Migration;
use panix\mod\discounts\models\Discount;

class m170908_104527_discounts extends Migration
{

    public function up()
    {

        $this->createTable(Discount::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sum' => $this->string(10)->notNull(),
            'start_date' => $this->datetime(),
            'end_date' => $this->datetime(),
            'roles' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
        ], $this->tableOptions);


        $this->createTable('{{%discount__category}}', [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'category_id' => $this->integer(),
        ], $this->tableOptions);


        $this->createTable('{{%discount__manufacturer}}', [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'manufacturer_id' => $this->integer(),
        ], $this->tableOptions);


        $this->createIndex('switch', '{{%discount}}', 'switch', 0);
        $this->createIndex('start_date', '{{%discount}}', 'start_date', 0);
        $this->createIndex('end_date', '{{%discount}}', 'end_date', 0);

        $this->createIndex('discount_id', '{{%discount__category}}', 'discount_id', 0);
        $this->createIndex('category_id', '{{%discount__category}}', 'category_id', 0);

        $this->createIndex('discount_id', '{{%discount__manufacturer}}', 'discount_id', 0);
        $this->createIndex('manufacturer_id', '{{%discount__manufacturer}}', 'manufacturer_id', 0);
    }

    public function down()
    {
        $this->dropTable('{{%discount}}');
        $this->dropTable('{{%discount__category}}');
        $this->dropTable('{{%discount__manufacturer}}');
    }

}
