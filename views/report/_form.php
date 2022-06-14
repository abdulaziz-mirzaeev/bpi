<?php
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

<h4 class="font-weight-normal">
    Please select months
</h4>

<div class="row">
    <div class="col">
        <?php echo $form->field($model, 'monthPrevious')->dropDownList($months); ?>
    </div>

    <div class="col">
        <?php echo $form->field($model, 'monthActual')->dropDownList($months); ?>
    </div>
    <div class="col">
        <?php echo $form->field($model, 'monthPlan')->dropDownList($months); ?>
    </div>
</div>

<?php echo $form->field($model, 'reportId')->dropDownList(['R7 Display', 'R8 Display'], ['prompt' => 'Select report...']); ?>

<?php echo Html::submitButton('Submit', ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end(); ?>