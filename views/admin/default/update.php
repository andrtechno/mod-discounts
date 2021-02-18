<?php

use panix\engine\Html;
use panix\engine\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use panix\mod\shop\models\Manufacturer;
use panix\engine\jui\DatetimePicker;
use panix\mod\shop\models\Category;

/**
 * @var \yii\web\View $this
 * @var \panix\mod\discounts\models\Discount $model
 * @var \panix\engine\bootstrap\ActiveForm $form
 */
$form = ActiveForm::begin(['id' => 'discount-form']);

?>
<div class="card">
    <div class="card-header">
        <h5><?= Html::encode($this->context->pageName) ?></h5>
    </div>
    <div class="card-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'sum')->textInput(['maxlength' => 10]) ?>
        <?= $form->field($model, 'start_date')->widget(DatetimePicker::class, [])->textInput(['maxlength' => 19, 'autocomplete' => 'off']) ?>
        <?= $form->field($model, 'end_date')->widget(DatetimePicker::class, [])->textInput(['maxlength' => 19, 'autocomplete' => 'off']) ?>

        <?= $form->field($model, 'manufacturers')
            ->dropDownList(ArrayHelper::map(Manufacturer::find()->all(), 'id', 'name'), [
                'prompt' => html_entity_decode($model::t('SELECT_EMPTY_MANUFACTURER')),
                'multiple' => 'multiple'
            ])->hint($model::t('HINT_MANUFACTURERS'));
        ?>


        <?= $form->field($model, 'userRoles')->dropDownList(ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', function ($role) {
            return html_entity_decode(((!empty($role->description)) ? '[' . $role->name . '] &mdash; ' . $role->description : $role->name));
        }), ['prompt' => html_entity_decode('&mdash; Укажите группу пользователей &mdash;'),'multiple' => true])->hint($model::t('HINT_USER_ROLES')); ?>

        <div class="form-group row">
            <div class="col-sm-4 col-lg-2">
                <?= Html::label(Yii::t('discounts/Discount', 'CATEGORIES')); ?>

            </div>
            <div class="col-sm-8 col-lg-10">
                <?= Html::label(Yii::t('app/default', 'Поиск:'), 'search-discount-category', ['class' => 'control-label']); ?>
                <?= Html::textInput('search', null, [
                    'id' => 'search-discount-category',
                    'class' => 'form-control',
                    'onChange' => '$("#CategoryTree").jstree("search", $(this).val());'
                ]); ?>
                <br/>
                <?php
                echo \panix\ext\jstree\JsTree::widget([
                    'id' => 'CategoryTree',
                    'allOpen' => true,
                    'data' => Category::find()->dataTree(1, null, ['switch' => 1]),
                    'core' => [
                        'strings' => [
                            'Loading ...' => Yii::t('app/default', 'LOADING')
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

                    $this->registerJs("$('#CategoryTree').checkNode({$id});", yii\web\View::POS_END, "checkNode{$id}");
                }

                ?>
            </div>
        </div>

    </div>
    <div class="card-footer text-center">
        <?= $model->submitButton(); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
