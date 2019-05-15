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

    public static $categoryTable = '{{%discount__category}}';
    public static $manufacturerTable = '{{%discount__manufacturer}}';

    public function up()
    {

        $this->createTable(Discount::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sum' => $this->string(10)->notNull(),
            'start_date' => $this->integer(11)->null(),
            'end_date' => $this->integer(11)->null(),
            'roles' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $this->tableOptions);


        $this->createTable(self::$categoryTable, [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'category_id' => $this->integer(),
        ], $this->tableOptions);


        $this->createTable(self::$manufacturerTable, [
            'id' => $this->primaryKey(),
            'discount_id' => $this->integer(),
            'manufacturer_id' => $this->integer(),
        ], $this->tableOptions);


        $this->createIndex('switch', Discount::tableName(), 'switch');
        $this->createIndex('start_date', Discount::tableName(), 'start_date');
        $this->createIndex('end_date', Discount::tableName(), 'end_date');

        $this->createIndex('discount_id', self::$categoryTable, 'discount_id');
        $this->createIndex('category_id', self::$categoryTable, 'category_id');

        $this->createIndex('discount_id', self::$manufacturerTable, 'discount_id');
        $this->createIndex('manufacturer_id', self::$manufacturerTable, 'manufacturer_id');
    }

    public function down()
    {
        $this->dropTable(Discount::tableName());
        $this->dropTable(self::$categoryTable);
        $this->dropTable(self::$manufacturerTable);
    }

}
