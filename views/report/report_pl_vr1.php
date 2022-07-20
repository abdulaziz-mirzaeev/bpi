<?php
/**
 * @var \app\models\ReportPL_VR1 $model
 * @var \yii\web\View $this
 */

use app\enums\RecordType;
use app\helpers\Tools;
use yii\helpers\Url;

$formatter = Yii::$app->formatter;
$date = $formatter->asDate($model->date, 'php:Y');

$this->registerJsVar('date', $date);

$actualYearLabel = RecordType::ACTUAL;
$planYearLabel = RecordType::PLAN;

function printMessageRow($message, $submessage) {
    echo <<<HTML
        <tbody>
            <tr>
                <td></td>
                <td colspan="10" class="fw-bold fst-italic">$message</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="10">$submessage</td>
            </tr>
        </tbody>
    HTML;
}
?>

<a class="btn btn-primary" href="<?php echo Url::to(Yii::$app->request->referrer); ?>">< Go Back</a>

<h4 class="text-center mb-4">
    <?php
    echo (Yii::$app->params['company']['name'] ?? 'Dybacco Constructions') . ' ' .
        'Actual-to-Plan P&L Variance Report for' . ' ' .
        $formatter->asDate($model->date, 'php:M-Y');
    ?>
</h4>

<table class="table table-borderless">
    <tbody class="text-center">
        <tr class="align-bottom">
            <th scope="col" class="align-middle">Actual-to-Plan P&L Variance Report</th>
            <th scope="col"><?php echo $date . ' ' . $planYearLabel; ?></th>
            <th scope="col">
                <span class="fw-light fs-6"><?php echo $formatter->asDate($model->date, 'php:M-y'); ?></span>
                <span class="d-block"><?php echo $date . ' ' . $actualYearLabel; ?></span>
            </th>
            <th scope="col"><?php echo $planYearLabel; ?> <span class="d-block">% of Sales</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">% of Sales</span></th>
            <th scope="col">Actual <span class="d-block">- Plan</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">% of Plan</span></th>
            <th scope="col">Low <span class="d-block">% of Plan</span></th>
            <th scope="col">High <span class="d-block">% of Plan</span></th>
            <th scope="col">In or Out <span class="d-block">% of Plan</span></th>
            <th scope="col">
                Select Three <span class="d-block">Areas to Act</span>
                <span class="d-block">Yes = Act</span>
            </th>
        </tr>
    </tbody>

    <tbody class="table-info">
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getNetSalesSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->actualNetSalesToPlanScoreInterpretation(), $model->netSalesScoreInterpretation()); ?>
    <?php Tools::printEmptyRow(); ?>

    <?php echo $this->render('_pl_vr1_subset_header', ['model' => $model, 'name' => 'Cost of Goods Sold']); ?>

    <tbody>
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getDirectCostCogsSubset()]); ?>
    </tbody>
    <tbody class="table-success">
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getGrossProfitSubset()]); ?>
    </tbody>
    <tbody>
        <tr>
            <th scope="row">Gross Margin</th>
            <td class="text-end"><?php echo $model->getPlanGrossMarginPercentage(); ?></td>
            <td class="text-end"><?php echo $model->getActualGrossMarginPercentage(); ?></td>
            <td class="text-end"></td>
            <td class="text-end fw-lighter">Red=Problem</td>
        </tr>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->grossProfitActualToPlanScoreInterpretation(), $model->grossProfitScoreInterpretation()); ?>
    <?php Tools::printEmptyRow(); ?>

    <?php echo $this->render('_pl_vr1_subset_header', ['model' => $model, 'name' => 'SG&A Expense']); ?>
    <tbody>
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getOperatingCostsAndSGA()]); ?>
    </tbody>
    <tbody class="table-success">
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getOperatingIncomeSubset()]); ?>
    </tbody>
    <tbody>
        <tr>
            <th scope="row">Operating Income Percent</th>
            <td class="text-end"><?php echo $model->getPlanOperatingIncomePercentage(); ?></td>
            <td class="text-end"><?php echo $model->getActualOperatingIncomePercentage(); ?></td>
            <td class="text-end"></td>
            <td class="text-end fw-lighter">Red=Problem</td>
        </tr>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->OIActualToPlanScoreInterpretation(), $model->OIScoreInterpretation()); ?>
    <?php Tools::printEmptyRow(); ?>

    <?php echo $this->render('_pl_vr1_subset_header', ['model' => $model, 'name' => 'Other Income & Expenses']); ?>
    <tbody>
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getOthersSubset()]); ?>
    </tbody>
    <tbody class="table-success">
        <?php echo $this->render('_pl_vr1_subset', ['recordPairs' => $model->getNetIncomeSubset()]); ?>
    </tbody>
    <tbody>
        <tr>
            <th scope="row">Return on Sales Percent</th>
            <td class="text-end"><?php echo $model->getPlanReturnOnSales(); ?></td>
            <td class="text-end"><?php echo $model->getActualReturnOnSales(); ?></td>
            <td class="text-end"></td>
            <td class="text-end fw-lighter">Red=Problem</td>
        </tr>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php printMessageRow($model->netIncomeActualToPlanScoreInterpretation(), $model->netIncomeScoreInterpretation());?>
</table>

<div class="card mt-5">
    <div class="card-header">
        <h6>Profit Plan Corrective Actions—select no more than three</h6>
    </div>
    <div class="card-body areas-to-act">

    </div>
</div>