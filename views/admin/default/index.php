<?php

use yii\widgets\Pjax;

Pjax::begin([
    'id' => 'pjax-container', 'enablePushState' => false,
    'linkSelector' => 'a:not(.linkTarget)'
]);
?>
<?=panix\engine\grid\GridView::widget([
    'tableOptions' => ['class' => 'table table-striped'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => $this->render('@app/web/themes/admin/views/layouts/_grid_layout', ['title' => $this->context->pageName]), //'{items}{pager}{summary}'
    'columns' => [

        'name',
        [
            'attribute' => 'sum',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'class' => 'panix\engine\grid\columns\ActionColumn',
            'template' => '{update} {switch} {delete}',
                ]
            ]
        ]);
        ?>
        <?php Pjax::end(); ?>


