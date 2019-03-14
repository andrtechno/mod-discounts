<?php

namespace panix\mod\discounts\models;

use Yii;
use panix\engine\db\ActiveRecord;

class Discount extends ActiveRecord
{

    const MODULE_ID = 'discounts';

    /**
     * @var array ids of categories to apply discount
     */
    //protected $_categories;

    /**
     * @var array ids of manufacturers to apply discount
     */
    //protected $_manufacturers;
    public $categories = [];
    public $useRules = [];
    public $manufacturers = [];
    protected $_discountCategories;
    protected $_discountManufacturers;

    public function attributeLabels()
    {
        return \yii\helpers\ArrayHelper::merge([
            'manufacturers' => self::t('MANUFACTURERS'),
            'userRoles' => self::t('USER_ROLES'),
        ], parent::attributeLabels());
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%discount}}';
    }

    public static function find()
    {
        return new DiscountQuery(get_called_class());
    }


    public function beforeSave($insert)
    {
        $this->start_date = strtotime($this->start_date);
        $this->end_date = strtotime($this->end_date);
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->start_date = date('Y-m-d H:i:s', $this->start_date);
        $this->end_date = date('Y-m-d H:i:s', $this->end_date);
        parent::afterFind();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['name', 'sum', 'start_date', 'end_date'], 'required'],
            ['switch', 'boolean'],
            ['name', 'string', 'max' => 255],
            ['sum', 'string', 'max' => 10],
            [['manufacturers', 'categories', 'userRoles'], 'each', 'rule' => ['integer']],
            [['start_date', 'end_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['id', 'name', 'switch', 'sum', 'start_date', 'end_date'], 'safe'],
        ];
    }


    /**
     * @param array $data
     */
    public function setDiscountCategories(array $data)
    {
        $this->_discountCategories = $data;
    }

    /**
     * @return array
     */
    public function getUserRoles()
    {
        return json_decode($this->roles);
    }

    /**
     * @param array $roles
     */
    public function setUserRoles(array $roles)
    {
        $this->roles = json_encode($roles);
    }

    /**
     * @return array
     */
    public function getDiscountManufacturers()
    {
        if (is_array($this->_discountManufacturers))
            return $this->_discountManufacturers;

        $this->_discountManufacturers = Yii::$app->db->createCommand('SELECT manufacturer_id FROM {{%discount__manufacturer}} WHERE discount_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();

        return $this->_discountManufacturers;
    }

    /**
     * @return array
     */
    public function getDiscountCategories()
    {
        if (is_array($this->_discountCategories))
            return $this->_discountCategories;

        $this->_discountCategories = Yii::$app->db->createCommand('SELECT category_id FROM {{%discount__category}} WHERE discount_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();

        return $this->_discountCategories;
    }

    /**
     * @param array $data
     */
    public function setDiscountManufacturers(array $data)
    {
        $this->_discountManufacturers = $data;
    }

    /**
     * After save event
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->clearRelations();

        // Process manufacturers
        if (!empty($this->manufacturers)) {
            foreach ($this->manufacturers as $id) {
                Yii::$app->db->createCommand()->insert('{{%discount__manufacturer}}', [
                    'discount_id' => $this->id,
                    'manufacturer_id' => $id,
                ])->execute();
            }
        }

        // Process categories
        if (!empty($this->categories)) {
            foreach (array_unique($this->categories) as $id) {

                Yii::$app->db->createCommand()->insert('{{%discount__category}}', [
                    'discount_id' => $this->id,
                    'category_id' => $id,
                ])->execute();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        //die('del');
        $this->clearRelations();
        parent::afterDelete();
    }

    /**
     * Clear discount manufacturer and category
     */
    public function clearRelations()
    {
        Yii::$app->db->createCommand()
            ->delete('{{%discount__manufacturer}}', 'discount_id=:id', [':id' => $this->id])
            ->execute();
        Yii::$app->db->createCommand()
            ->delete('{{%discount__category}}', 'discount_id=:id', [':id' => $this->id])
            ->execute();

    }

}
