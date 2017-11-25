<?php

use yii\helpers\Html;
use panix\engine\bootstrap\ActiveForm;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->context->pageName) ?></h3>
    </div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'id' => basename(get_class($model)),
                    'options' => [
                        'class' => 'form-horizontal',
                    ]
        ]);
        echo yii\bootstrap\Tabs::widget([
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
        <div class="form-group text-center">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'CREATE') : Yii::t('app', 'UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

