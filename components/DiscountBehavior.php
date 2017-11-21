<?php

namespace panix\mod\discounts\components;

use Yii;
use yii\db\ActiveRecord;
use panix\mod\discounts\models\Discount;

class DiscountBehavior extends \yii\base\Behavior {

    /**
     * @var mixed|null|Discount
     */
    public $appliedDiscount = null;

    /**
     * @var float product price before discount applied
     */
    public $originalPrice;
    public $discountPrice;
    public $discountSum;
    public $discountSumNum;
    public $discountEndDate;

    /**
     * @var null
     */
    private $discounts = null;

    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * Attach behavior to model
     * @param $owner
     */
    public function attach($owner) {
        parent::attach($owner);
        if ($this->discounts === null) {

            $this->discounts = Discount::find()
                    ->published()
                    ->applyDate()
                    ->all();
        }
    }

    /**
     * After find event
     */
    public function afterFind() {

        if ($this->appliedDiscount !== null)
            return;

        $user = Yii::$app->user;

        // Personal product discount
        if (!empty($this->owner->discount)) {
            $discount = new Discount();
            $discount->name = Yii::t('app', 'Скидка');
            $discount->sum = $this->owner->discount;
            $this->applyDiscount($discount);
        }

        // Process discount rules
        if (!$this->hasDiscount()) {

            foreach ($this->discounts as $discount) {

                $apply = false;

                // Validate category
                if ($this->searchArray($discount->discountCategories, $this->ownerCategories)) {
                    $apply = true;

                    // Validate manufacturer
                    if (!empty($discount->discountManufacturers))
                        $apply = in_array($this->owner->manufacturer_id, $discount->discountManufacturers);

                    // Apply discount by user role. Discount for admin disabled.
                    if (!empty($discount->userRoles)) {
                        //if (!empty($discount->userRoles) && $user->checkAccess('Admin') !== true) {
                        $apply = false;

                        foreach ($discount->userRoles as $role) {
                            if ($user->checkAccess($role)) {
                                $apply = true;
                                break;
                            }
                        }
                    }
                }

                if ($apply === true) {
                    $this->applyDiscount($discount);
                }
            }
        }

        // Personal discount for users.
        if (!$user->isGuest && !empty($user->discount) && !$this->hasDiscount()) {
            $discount = new Discount();
            $discount->name = Yii::t('app', 'Персональная скидка');
            $discount->sum = $user->discount;
            $this->applyDiscount($discount);
        }
    }

    /**
     * Apply discount to product and decrease its price
     * @param Discount $discount
     */
    protected function applyDiscount(Discount $discount) {

        if ($this->appliedDiscount === null) {

            $sum = $discount->sum;
            if ('%' === substr($discount->sum, -1, 1)) {
                $sum = $this->owner->price * (int) $sum / 100;
            }
            $this->originalPrice = $this->owner->price;
            $this->discountPrice = $this->owner->price - $sum;

            $this->discountEndDate = $discount->end_date;
            $this->discountSum = $discount->sum;
            $this->discountSumNum = $sum;
            $this->appliedDiscount = $discount;
        }
    }

    /**
     * Search value from $a in $b
     * @param array $a
     * @param array $b
     * @return array
     */
    protected function searchArray(array $a, array $b) {
        foreach ($a as $v)
            if (in_array($v, $b))
                return true;
        return false;
    }

    /**
     * @return array
     */
    public function getOwnerCategories() {
        $id = 'discount_product_categories' . $this->owner->date_update;
        $data = Yii::$app->cache->get($id);

        if ($data === false) {
            $data = \yii\helpers\ArrayHelper::map($this->owner->categories, 'id', 'id');
            Yii::$app->cache->set($id, $data);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasDiscount() {
        return !($this->appliedDiscount === null);
    }

}
