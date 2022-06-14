<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'excelFile')->fileInput(); ?>

<?= $form->field($model, 'column')->textInput()->hint('Enter column name (e.g. BC)'); ?>

<?= $form->field($model, 'overwrite')->checkbox()->hint('Overwrite if data exists') ?>

<?= Html::submitButton('Submit', ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end() ?>
