<?php
/**
 * @var ReportPL_VR2 $model
 * @var \yii\web\View $this
 */

use app\enums\AccountId;
use app\enums\RecordType;
use app\helpers\Tools;
use app\models\ReportPL_VR2;
use yii\helpers\Url;

$formatter = Yii::$app->formatter;
$date = $formatter->asDate($model->date, 'php:Y');
$datePrevious = $formatter->asDate($model->datePrevious, 'php:Y');

$this->registerJsVar('date', $date);
$this->registerJsVar('datePrevious', $datePrevious);

$actualYearLabel = RecordType::ACTUAL;
$planYearLabel = RecordType::ACTUAL;

function printMessageRow($message, $submessage) {
    echo <<<HTML
        <tbody>
            <tr>
                <td></td>
                <td colspan="11" class="fw-bold fst-italic">$message</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="11">$submessage</td>
            </tr>
        </tbody>
    HTML;
}

function printMarginRow($accountId, ReportPL_VR2 $model, $name) {
    [$previousYear, $currentYear, $actualChangeInDollars] = $model->getMarginsByAccountId($accountId);
    echo <<<HTML
        <tbody>
            <tr>
                <th scope="row">$name</th>
                <td class="text-end">$previousYear</td>
                <td class="text-end">$currentYear</td>
                <td class="text-end">$actualChangeInDollars</td>
            </tr>
        </tbody>
    HTML;
}
?>

<a class="btn btn-primary" href="<?php echo Url::to(Yii::$app->request->referrer); ?>">< Go Back</a>

<h4 class="text-center mb-4">
    <?php
    echo (Yii::$app->params['company']['name'] ?? 'Dybacco Constructions') . ' ' .
        'Year-Over-Year P&L Variance Report for' . ' ' .
        $formatter->asDate($model->date, 'php:M-Y');
    ?>
</h4>

<table class="table table-borderless">
    <!--region Net Sales Header-->
    <tbody class="text-center">
        <tr class="align-bottom">
            <th scope="col" class="align-middle">Year-Over-Year P&L Variance Report</th>
            <th scope="col">
                <span class="fw-light fs-6">
                    <?php echo $formatter->asDate($model->datePrevious, 'php:M-y'); ?>
                </span>
                <span class="d-block"><?php echo $datePrevious . ' ' . $planYearLabel; ?></span>
            </th>
            <th scope="col">
                <span class="fw-light fs-6"><?php echo $formatter->asDate($model->date, 'php:M-y'); ?></span>
                <span class="d-block"><?php echo $date . ' ' . $actualYearLabel; ?></span>
            </th>
            <th scope="col">Curr - Prev</span></th>
            <th scope="col">% Change</th>
            <th scope="col">Prev YR <span class="d-block">% of Plan</span></th>
            <th scope="col">Curr YR <span class="d-block">% of Plan</span></th>
            <th scope="col">NA</th>
            <th scope="col">Low <span class="d-block">% of Plan</span></th>
            <th scope="col">High <span class="d-block">% of Plan</span></th>
            <th scope="col">Sales <span class="d-block">Low/High</span></th>
            <th scope="col">
                Select Three <span class="d-block">Areas to Act</span>
                <span class="d-block">Yes = Act</span>
            </th>
        </tr>
    </tbody>
    <!--endregion-->

    <tbody class="table-info">
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getNetSalesSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->interpretationNS_1_1(), $model->interpretationNS_1_0()); ?>
    <?php Tools::printEmptyRow(); ?>

    <?php echo $this->render('_pl_vr2_subset_header', [
            'model' => $model,
            'name' => 'Cost of Goods Sold',
            'subsetName' => 'COGS',
    ]); ?>

    <tbody>
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getDirectCostCogsSubset()]); ?>
    </tbody>
    <tbody class="table-success">
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getGrossProfitSubset()]); ?>
    </tbody>
    <?php printMarginRow(AccountId::GROSS_PROFIT, $model, 'Gross Margin'); ?>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->interpretationGP_2_1(), $model->interpretationGP_2_0()); ?>
    <?php Tools::printEmptyRow(); ?>

    <?php echo $this->render('_pl_vr2_subset_header', ['model' => $model, 'name' => 'SG&A Expense']); ?>
    <tbody>
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getOperatingCostsAndSGA()]); ?>
    </tbody>
    <tbody class="table-success">
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getOperatingIncomeSubset()]); ?>
    </tbody>
    <?php printMarginRow(AccountId::OPERATING_INCOME, $model, 'Operating Income Percent'); ?>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->interpretationOI_3_1(), $model->interpretationOI_3_0()); ?>
    <?php Tools::printEmptyRow(); ?>

    <?php echo $this->render('_pl_vr2_subset_header', ['model' => $model, 'name' => 'Other Income & Expenses']); ?>
    <tbody>
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getOthersSubset()]); ?>
    </tbody>
    <tbody class="table-success">
        <?php echo $this->render('_pl_vr2_subset', ['recordPairs' => $model->getNetIncomeSubset()]); ?>
    </tbody>
    <?php printMarginRow(AccountId::NET_INCOME, $model, 'Return on Sales Percent'); ?>

    <?php Tools::printEmptyRow(); ?>
    <?php  printMessageRow($model->interpretationNI_4_1(), $model->interpretationNI_4_0());?>
</table>

<div class="card mt-5">
    <div class="card-header">
        <h6>Profit Plan Corrective Actions—select no more than three</h6>
    </div>
    <div class="card-body areas-to-act">

    </div>
</div>