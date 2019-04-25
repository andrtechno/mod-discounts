<?php
use panix\mod\shop\models\CategoryNode;
use panix\mod\shop\models\Category;

?>
<div class="form-group row2">
    <?php
    echo \panix\engine\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'closeButton' => false,
        'body' => Yii::t('discounts/default', "CATEGORY_INFO"),
    ]);
    ?>
</div>

<div class="form-group row">
    <div class="col-sm-4"><label class="control-label"
                                 for="search-discount-category"><?php echo Yii::t('app', 'Поиск:') ?></label></div>
    <div class="col-sm-8"><input class="form-control" id="search-discount-category" type="text"
                                 onkeyup='$("#jsTree_DiscountCategoryTree").jstree("search", $(this).val());'/>
    </div>
</div>


<?php

echo \panix\ext\jstree\JsTree::widget([
    'id' => 'DiscountCategoryTree',
    'name' => 'jstree',
    'allOpen' => true,
    'data' => CategoryNode::fromArray(Category::findOne(1)->children()->all()),
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

foreach ($model->getDiscountCategories() as $id) {

    $this->registerJs("$('#jsTree_DiscountCategoryTree').checkNode({$id});", yii\web\View::POS_END, "checkNode{$id}");
}

// Create jstree
/*$this->widget('ext.jstree.JsTree', array(
    'id' => 'ShopDiscountCategoryTree',
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk(1)),
    'options' => array(
        'core' => array(
            'check_callback' => true,
            "themes" => array("stripes" => true, 'responsive' => true),
        ),
        'plugins' => array('search', 'checkbox'),
        'checkbox' => array(
            'three_state'=>false,
            "keep_selected_style" => false,
            'tie_selection' => false,
        ),
    ),
));

// Check tree nodes
foreach ($model->categories as $id) {
    Yii::app()->getClientScript()->registerScript("checkNode{$id}", "$('#ShopDiscountCategoryTree').checkNode({$id});");
}*/
?>
