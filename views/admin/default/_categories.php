<?php
use panix\mod\shop\models\Category;
use panix\engine\Html;

?>
<div class="form-group row2">
    <div class="alert alert-info"><?= Yii::t('discounts/default', "CATEGORY_INFO"); ?></div>
</div>

<div class="form-group row">
    <div class="col-sm-4">
        <?= Html::label(Yii::t('app', 'Поиск:'),'search-discount-category',['class'=>'control-label']);?>
    </div>
    <div class="col-sm-8">
        <?= Html::textInput('search', null, [
            'id' => 'search-discount-category',
            'class' => 'form-control',
            'onClick' => '$("#jsTree_CategoryTree").jstree("search", $(this).val());'
        ]); ?>
    </div>
</div>


<?php

echo \panix\ext\jstree\JsTree::widget([
    'id' => 'CategoryTree',
    'name' => 'jstree',
    'allOpen' => true,
    'data' => Category::find()->dataTree(1),
    'core' => [
        'strings' => [
            'Loading ...' => Yii::t('app', 'LOADING')
        ],
        'check_callback' => true,
        "themes" => [
            "stripes" => true,
            'responsive' => true,
            "variant" => "large",
            // 'name' => 'default-dark',
            // "dots" => true,
            // "icons" => true
        ],
    ],
    'plugins' => ['search', 'checkbox'],
    'checkbox' => [
        'three_state' => false,
        "keep_selected_style" => false,
        'tie_selection' => false,
    ],
]);

foreach ($model->getCategories() as $id) {

    $this->registerJs("$('#jsTree_CategoryTree').checkNode({$id});", yii\web\View::POS_END, "checkNode{$id}");
}

