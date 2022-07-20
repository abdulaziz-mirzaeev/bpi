<?php
/**
 * @var \Tightenco\Collect\Support\Collection $actual
 * @var \Tightenco\Collect\Support\Collection $planned
 * @var string $actualYear
 * @var string $planYear
 * @var Account[] $accounts
 * @var integer $reportType
 * @var array $display_r7
 * @var array $display_r8
 *
 * @var \yii\web\View $this
 */

use app\enums\RecordType;
use app\helpers\F;
use app\models\Account;
use app\models\Record;
use app\models\ReportForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<h2 class="text-center">Dybaco Construction</h2>
<h4 class="text-center fw-light text-uppercase">BPI Scoreboards</h4>
<div class="row justify-content-center">
    <p class="col-lg-8">
        Highly profitable business owners with sustainable cash reserves set aside time each month to review their
        business results.
        They know that their profit plan is their best measurement for success.
        They use it as a basis for comparison to help them confirm what's working well in their business
        and what they need to fix to make more money.
    </p>
</div>

<?php
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
    13 => 'YTD',
];
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?php echo $form->field($model, 'month')->dropDownList($months)->hint('Please select month'); ?>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-6">
        <?php echo $form
            ->field($model, 'reportId')
            ->dropDownList(ReportForm::$reportNames, ['prompt' => 'Select report...']);
        ?>
    </div>
</div>

<div class="d-grid gap-2 col-6 mx-auto">
    <?php echo Html::submitButton('Find', ['class' => 'btn btn-success px-5 float-end']); ?>
</div>

<?php ActiveForm::end(); ?>