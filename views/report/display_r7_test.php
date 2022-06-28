<?php
/**
 * @var \Tightenco\Collect\Support\Collection $actual
 * @var \Tightenco\Collect\Support\Collection $planned
 * @var string $actualYear
 * @var string $planYear
 * @var Account[] $accounts
 *
 * @var \app\models\ReportR7 $model
 * @var \yii\web\View $this
 */

use app\enums\RecordType;
use app\helpers\F;
use app\models\Account;
use app\models\Record;

$formatter = Yii::$app->formatter;
$date = $formatter->asDate($model->date, 'php:Y');

$actualYearLabel = $date . ' ' . RecordType::ACTUAL;
$planYearLabel = $date . ' ' . RecordType::PLAN;
?>

<h2 class="my-4 display-6">R7 Display - <?php echo $formatter->asDate($model->date, 'php:M-Y') ?></h2>

<table class="table table-borderless table-hover">
    <thead class="text-center">
        <tr>
            <th scope="col" rowspan="2">Actual To Plan</th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">($)</span></th>
            <th scope="col"><?php echo $planYearLabel; ?> <span class="d-block">($)</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">% of Plan</span></th>
            <th scope="col">Act - Plan <span class="d-block">($)</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">% of Sales</span></th>
            <th scope="col"><?php echo $planYearLabel; ?> <span class="d-block">% of Sales</span></th>
            <th scope="col">Act - Plan <span class="d-block">Difference</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">$100 Equiv.</span></th>
            <th scope="col"><?php echo $planYearLabel; ?> <span class="d-block">$100 Equiv.</span></th>
            <th scope="col">Act - Plan <span class="d-block">Difference</span></th>
        </tr>
    </thead>
    <tbody>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getNetSalesSubset()]); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getDirectCostsSubset()]); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getGrossProfitSubset()]); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getOperatingCostsSubset()]); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getOperatingIncomeSubset()]); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getOthersSubset()]); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getNetIncomeSubset()]); ?>
    </tbody>
</table>

