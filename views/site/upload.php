<?php

use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'excelFile')->fileInput(); ?>

<?= $form->field($model, 'column')->widget(Select2::class, [
    'data' => [],
    'options' => ['multiple' => true],
    'pluginOptions' => [
        'tags' => true,
        'formatNoMatches' => function () {
            return '';
        },
        'dropdownCssClass' => 'd-none',
    ]
])->hint('Enter column names (e.g. BC)'); ?>

<?= $form->field($model, 'overwrite')->checkbox()->hint('Overwrite if data exists') ?>

<?= Html::submitButton('Submit', ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end() ?>
