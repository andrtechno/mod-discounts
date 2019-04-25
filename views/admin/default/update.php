<?php

use yii\helpers\Html;
use panix\engine\bootstrap\ActiveForm;



?>
<div class="card">
    <div class="card-header">
        <h5><?= Html::encode($this->context->pageName) ?></h5>
    </div>
    <div class="card-body">
        <?php
        $form = ActiveForm::begin([
            'id' => basename(get_class($model)),
            'options' => [
                'class' => 'form-horizontal',
            ]
        ]);
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
        // echo $this->render('_categories', ['form' => $form, 'model' => $model]);
        ?>
        <div class="form-group text-center">
            <?= $model->submitButton(); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

