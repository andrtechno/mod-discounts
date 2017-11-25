<?php


use yii\helpers\ArrayHelper;
use panix\mod\shop\models\Manufacturer;
use panix\engine\jui\DatePicker;


?>
<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'sum')->textInput(['maxlength' => 255]) ?>

<?= $form->field($model, 'start_date')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'end_date')->textInput(['maxlength' => 255]) ?>




<?= $form->field($model, 'manufacturers')
        ->dropDownList(ArrayHelper::map(Manufacturer::find()->all(), 'id', 'name'), [
    'prompt' => 'Укажите производителя',
            'multiple'=>'multiple'
])->hint('Чтобы скидка заработала, необходимо указать категорию'); ?>

