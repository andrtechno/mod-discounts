<?php

use yii\helpers\Html;
use panix\engine\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id'=>'discount-form']);

?>
<div class="card">
    <div class="card-header">
        <h5><?= Html::encode($this->context->pageName) ?></h5>
    </div>
    <div class="card-body">
        <?php
        echo panix\engine\bootstrap\Tabs::widget([
            'items' => [
                [
                    'label' => $model::t('TAB_MAIN'),
                    'content' => $this->render('_main', ['form' => $form, 'model' => $model]),
                    'active' => true,
                    'options' => ['id' => 'main'],
                ],
                [
                    'label' => $model::t('TAB_CATEGORIES'),
                    'content' => $this->render('_categories', ['form' => $form, 'model' => $model]),
                    'headerOptions' => [],
                    'options' => ['id' => 'categories'],
                ],
            ],
        ]);
        ?>
    </div>
    <div class="card-footer text-center">
        <?= $model->submitButton(); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
