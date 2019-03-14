<?php

namespace panix\mod\discounts\models;

use yii\db\ActiveQuery;

class DiscountQuery extends ActiveQuery {

    public function published($state = 1) {
        return $this->andWhere(['switch' => $state]);
    }

    public function applyDate() {
        return $this->andWhere(['<=', 'start_date', time()])->andWhere(['>=', 'end_date', time()]);
    }

}
