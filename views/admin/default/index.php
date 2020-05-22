<?php

use panix\engine\widgets\Pjax;
use panix\engine\grid\GridView;
use panix\engine\CMS;
use panix\engine\Html;
use yii\helpers\ArrayHelper;
use panix\mod\shop\models\Category;

Pjax::begin([
    'dataProvider' => $dataProvider
]);
$categories = ArrayHelper::map(Category::find()->excludeRoot()->asArray()->all(), 'id', 'name_'.Yii::$app->language);
$manufacturers = ArrayHelper::map(\panix\mod\shop\models\Manufacturer::find()->asArray()->all(),'id','name_'.Yii::$app->language);
//CMS::dump($manufacturers);
echo GridView::widget([
    'tableOptions' => ['class' => 'table table-striped'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layoutOptions' => ['title' => $this->context->pageName],
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return Html::tag('span', $model->sum, ['class' => 'badge badge-danger']) . ' ' . $model->name;
            }
        ],
        [
            'attribute' => 'categories',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
            'filter' => Html::dropDownList(Html::getInputName(new \panix\mod\discounts\models\DiscountSearch(), 'categories'), (isset(Yii::$app->request->get('DiscountSearch')['categories'])) ? Yii::$app->request->get('DiscountSearch')['categories'] : null, Category::flatTree(),
                [
                    'class' => 'form-control',
                    'prompt' => html_entity_decode('&mdash; выберите категорию &mdash;')
                ]
            ),
            'value' => function ($model) use ($categories) {
                $result = '';
                foreach ($model->categories as $category) {
                    $options['class'] = 'badge badge-secondary';
                    $result .= Html::tag('span', $categories[$category], $options);
                }
                return $result;
            }
        ],
        [
            'attribute' => 'manufacturers',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
            'filter' => Html::dropDownList(Html::getInputName(new \panix\mod\discounts\models\DiscountSearch(), 'manufacturers'), (isset(Yii::$app->request->get('DiscountSearch')['manufacturers'])) ? Yii::$app->request->get('DiscountSearch')['manufacturers'] : null, $manufacturers,
                [
                    'class' => 'form-control',
                    'prompt' => html_entity_decode('&mdash; выберите manufacturers &mdash;')
                ]
            ),
            'value' => function ($model) use ($manufacturers) {
                $result = '';
                foreach ($model->manufacturers as $manufacturer) {
                    $options['class'] = 'badge badge-secondary';
                    $result .= Html::tag('span', $manufacturers[$manufacturer], $options);
                }
                return $result;
            }
        ],
        [
            'class' => 'panix\engine\grid\columns\jui\DatepickerColumn',
            'attribute' => 'start_date',
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return CMS::date(strtotime($model->start_date));
            }
        ],
        [
            'class' => 'panix\engine\grid\columns\jui\DatepickerColumn',
            'attribute' => 'end_date',
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return CMS::date(strtotime($model->end_date));
            }
        ],
        [
            'class' => 'panix\engine\grid\columns\ActionColumn',
        ]
    ]
]);
Pjax::end();

