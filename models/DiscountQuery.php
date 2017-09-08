<?php

namespace panix\mod\discounts\models;

use yii\db\ActiveQuery;

class DiscountQuery extends ActiveQuery {

    public function published($state = 1) {
        return $this->andWhere(['switch' => $state]);
    }

    public function applyDate() {
        $date = date('Y-m-d H:i:s');
        return $this->andWhere(['<=', 'start_date', $date])->andWhere(['>=', 'end_date', $date]);
    }

}
