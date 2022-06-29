<?php

use app\models\ReportForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$form = ActiveForm::begin(['method' => 'get']); ?>

<?php
$months = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December',
];
?>

<h4 class="fw-normal">
    Please select month
</h4>

<?php echo $form->field($model, 'month')->dropDownList($months); ?>

<?php echo $form
    ->field($model, 'reportId')
    ->dropDownList(ReportForm::$reportNames, ['prompt' => 'Select report...']);
?>

<?php echo Html::submitButton('Submit', ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end(); ?>