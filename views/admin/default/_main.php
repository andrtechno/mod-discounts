<?php
use yii\helpers\ArrayHelper;
use panix\mod\shop\models\Manufacturer;
use panix\engine\jui\DatetimePicker;

/**
 * @var \panix\mod\discounts\models\Discount $model
 * @var \panix\engine\bootstrap\ActiveForm $form
 */

?>
<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'sum')->textInput(['maxlength' => 10]) ?>
<?= $form->field($model, 'start_date')->widget(DatetimePicker::class, [])->textInput(['maxlength' => 19,'autocomplete'=>'off']) ?>
<?= $form->field($model, 'end_date')->widget(DatetimePicker::class, [])->textInput(['maxlength' => 19,'autocomplete'=>'off']) ?>

<?= $form->field($model, 'manufacturers')
    ->dropDownList(ArrayHelper::map(Manufacturer::find()->all(), 'id', 'name'), [
        'prompt' => 'Укажите производителя',
        'multiple' => 'multiple'
    ])->hint('Чтобы скидка заработала, необходимо указать категорию');
?>

