<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/*
 * @var yii\web\View $this
 * @var common\models\Group $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'users')->checkboxList(ArrayHelper::map(User::find()->all(), 'id', 'username')) ?>

    <div class="form-group mt-4">
        <?php echo Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

