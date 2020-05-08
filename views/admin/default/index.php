<?php

use panix\engine\widgets\Pjax;
use panix\engine\grid\GridView;
use panix\engine\CMS;

Pjax::begin([
    'dataProvider'=>$dataProvider
]);


echo GridView::widget([
    'tableOptions' => ['class' => 'table table-striped'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layoutOptions' => ['title' => $this->context->pageName],
    'columns' => [
        'name',
        [
            'attribute' => 'sum',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
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

