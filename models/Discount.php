<?php

namespace panix\mod\discounts\models;

use panix\engine\CMS;
use panix\mod\shop\components\ProductPriceHistoryDiscountQueue;
use panix\mod\shop\models\Product;
use Yii;
use panix\engine\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class Discount
 *
 * @property integer $id
 * @property string $name
 * @property string $sum
 * @property array $categories Category ids
 * @property array $manufacturers Manufacturer ids
 * @property integer $start_date
 * @property integer $end_date
 *
 * @package panix\mod\discounts\models
 *
 */
class Discount extends ActiveRecord
{

    const MODULE_ID = 'discounts';

    /**
     * @var array ids of categories to apply discount
     */
    protected $_categories;

    /**
     * @var array ids of manufacturers to apply discount
     */
    protected $_manufacturers;

    public static function tableNameCategories()
    {
        return '{{%discount__category}}';
    }

    public static function tableNameManufacturers()
    {
        return '{{%discount__manufacturer}}';
    }

    public function attributeLabels()
    {
        return \yii\helpers\ArrayHelper::merge([
            'manufacturers' => self::t('MANUFACTURERS'),
            'categories' => self::t('CATEGORIES'),
            'userRoles' => self::t('USER_ROLES'),
        ], parent::attributeLabels());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%discount}}';
    }

    public static function find()
    {
        return new DiscountQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sum', 'start_date', 'end_date'], 'required'],
            ['switch', 'boolean'],
            ['name', 'string', 'max' => 255],
            ['sum', 'string', 'max' => 10],
            [['created_at', 'updated_at'], 'integer'],
            //[['discountManufacturers', 'discountCategories', 'userRoles'], 'each', 'rule' => ['integer']],
            [['manufacturers', 'categories'], 'validateArray'],
            //[['manufacturers', 'categories'], 'default', 'value' => []],
            // [['userRoles'], 'default', 'value' => []],
            ['userRoles', 'each', 'rule' => ['string']],
            [['start_date', 'end_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['id', 'name', 'switch', 'sum', 'start_date', 'end_date'], 'safe'],
        ];
    }

    public function validateArray($attribute)
    {
        if (!is_array($this->{$attribute})) {
            $this->addError($attribute, 'The attribute must be array.');
        }
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
    public function setUserRoles($roles)
    {
        $this->roles = json_encode($roles);
    }


    /**
     * @param array $data
     */
    public function setCategories($data)
    {
        $this->_categories = $data;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        if (is_array($this->_categories))
            return $this->_categories;

        $this->_categories = self::getDb()->createCommand('SELECT category_id FROM ' . self::tableNameCategories() . ' WHERE discount_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();

        return $this->_categories;
    }

    /**
     * @param array $data
     */
    public function setManufacturers($data)
    {
        $this->_manufacturers = $data;
    }


    /**
     * @return array
     */
    public function getManufacturers()
    {
        if (is_array($this->_manufacturers))
            return $this->_manufacturers;

        $this->_manufacturers = self::getDb()->createCommand('SELECT manufacturer_id FROM ' . self::tableNameManufacturers() . ' WHERE discount_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();


        return $this->_manufacturers;
    }

    /**
     * Clear discount manufacturer and category
     */
    public function clearRelations()
    {
        self::getDb()->createCommand()
            ->delete(self::tableNameManufacturers(), 'discount_id=:id', [':id' => $this->id])
            ->execute();
        self::getDb()->createCommand()
            ->delete(self::tableNameCategories(), 'discount_id=:id', [':id' => $this->id])
            ->execute();

    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->start_date = strtotime($this->start_date);
        $this->end_date = strtotime($this->end_date);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->start_date = date('Y-m-d H:i:s', $this->start_date);
        $this->end_date = date('Y-m-d H:i:s', $this->end_date);
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->clearRelations();
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!isset($changedAttributes['switch'])) {

            $this->clearRelations();

            // Process manufacturers
            if (!empty($this->_manufacturers)) {
                foreach ($this->_manufacturers as $id) {
                    self::getDb()->createCommand()->insert(self::tableNameManufacturers(), [
                        'discount_id' => $this->id,
                        'manufacturer_id' => $id,
                    ])->execute();
                }
            }

            // Process categories
            if (!empty($this->_categories)) {
                foreach (array_unique($this->_categories) as $id) {

                    self::getDb()->createCommand()->insert(self::tableNameCategories(), [
                        'discount_id' => $this->id,
                        'category_id' => $id,
                    ])->execute();
                }
            }

        }


        if (!$insert && Yii::$app->queue) {

            if (isset($changedAttributes['sum']) || isset($changedAttributes['switch'])) {

                if ($this->categories || $this->manufacturers) {

                    $query = Product::find();

                    if ($this->categories) {
                        $query->applyCategories($this->categories);
                    }
                    if ($this->manufacturers) {
                        $query->applyManufacturers($this->manufacturers);
                    }

                    $query->asArray();//->select([Product::tableName() . '.`id`', Product::tableName() . '.`price`', Product::tableName() . '.`price_purchase`', Product::tableName() . '.`currency_id`']);


                    if (isset($changedAttributes['sum'])) {
                        if (($changedAttributes['sum'] <> $this->attributes['sum'])) {
                            $ch_sum = $changedAttributes['sum'];
                            if (strpos($ch_sum, '%')) {
                                $ch_sum = (double)str_replace('%', '', $ch_sum);
                            }

                            $attr_sum = $this->attributes['sum'];
                            if (strpos($attr_sum, '%')) {
                                $attr_sum = (double)str_replace('%', '', $attr_sum);
                            }
                            $q_event = 'change';
                            $type = ($ch_sum < $attr_sum) ? 1 : 0;
                        }
                    }
                    if (isset($changedAttributes['switch'])) {
                        $q_event = ($this->attributes['switch']) ? 'change' : 'switch_off';
                        $type = ($this->attributes['switch']) ? 1 : 0;
                        $attr_sum = $this->attributes['sum'];
                    }

                    // $query->attachBehavior('discount',
                    //         '\panix\mod\discounts\components\DiscountBehavior'
                    //  );

                    foreach ($query->indexBy('id')->batch(500) as $items) {

                        Yii::$app->queue->push(new ProductPriceHistoryDiscountQueue([
                            'items' => array_keys($items),
                            'type' => $type,
                            'value' => $attr_sum,
                            'q_event' => $q_event
                        ]));
                    }
                }
            }
        }


        parent::afterSave($insert, $changedAttributes);
    }


}
