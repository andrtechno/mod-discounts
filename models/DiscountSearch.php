<?php

namespace panix\mod\discounts\models;


use yii\base\Model;
use panix\engine\data\ActiveDataProvider;
use panix\mod\discounts\models\Discount;


class DiscountSearch extends Discount
{
    public $categories;
    public $manufacturers;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'categories', 'manufacturers'], 'integer'],
            [['name'], 'safe'],
            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Discount::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        if ($this->categories) {
            $query->leftJoin(self::tableNameCategories(), Discount::tableName() . '.`id` = ' . self::tableNameCategories() . '.`discount_id`');
            $query->andFilterWhere([self::tableNameCategories() . '.category_id' => $this->categories]);
        }

        if ($this->manufacturers) {
            $query->leftJoin(self::tableNameManufacturers(), Discount::tableName() . '.`id` = ' . self::tableNameManufacturers() . '.`discount_id`');
            $query->andFilterWhere([self::tableNameManufacturers() . '.manufacturer_id' => $this->manufacturers]);
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        foreach (['end_date', 'start_date', 'created_at', 'updated_at'] as $date) {
            $query->andFilterWhere(['>=', $date, $this->{$date} ? strtotime($this->{$date} . ' 00:00:00') : null]);
            $query->andFilterWhere(['<=', $date, $this->{$date} ? strtotime($this->{$date} . ' 23:59:59') : null]);
        }

        return $dataProvider;
    }

}
