<?php
/**
 * @var \app\models\ReportYOY $model
 * @var \yii\web\View $this
 */

use app\enums\RecordType;
use app\helpers\F;
use app\helpers\Tools;
use app\models\Account;
use app\models\Record;

$formatter = Yii::$app->formatter;
$date = $formatter->asDate($model->date, 'php:Y');
$datePrevious = $formatter->asDate($model->datePrevious, 'php:Y');

$actualYearLabel = $date . ' ' . RecordType::ACTUAL;
$previousYearLabel = $datePrevious . ' ' . RecordType::ACTUAL;

function printThresholdCell($value, $message, $position = 5): string {
    $newPosition = $position - 1;
    return <<<HTML
        <td colspan="$newPosition" class="fw-light text-muted text-end">
            <small>$message</small>
        </td>
        <td class="table-primary text-end">
            $value
        </td>
    HTML;
}
?>

<h2 class="text-center"><?php echo Yii::$app->params['company']['name'] ?? 'Dybacco Constructions'; ?></h2>
<h2 class="mb-4 text-center"><?php echo $formatter->asDate($model->date, 'php:M-Y'); ?></h2>
<h2 class="mb-4 display-6 text-center">Year-Over-Year P&L Table</h2>

<p class="text-center">Below is your year-over-year results to appreciate where you did better and worse this month compared to same month last year.</p>
<p class="text-center">The green highlighted cells in your P&L results table show where you lowered your costs as a percent of sales below what you spent last year.   How much you saved this year over last.
</p>
<p class="text-center">
    Every item shaded red reflects a potential problem area for your business.  It indicates that you either had a significant change in that category to drive up your costs this year over last or a data capture problem.  Your goal is to understand what drove the change and then correct immediately where you are losing money.
</p>
<p class="text-center">
    Use the light shaded blue cells to adjust your sales, direct, and indirect cost acceptable change range should you wish to better dial in your ability to quickly see areas of improvement you can celebrate or concern you need to fix.
</p>

<table class="table table-borderless">
    <thead class="text-center">
        <tr>
            <td class="text-center fw-light text-secondary">
                <small>Note 1</small>
            </td>
            <td>
                <?php echo $formatter->asDate($model->date, 'php:M-y'); ?>
            </td>
            <td>
                <?php echo $formatter->asDate($model->datePrevious, 'php:M-y'); ?>
            </td>
            <td></td>
            <td class="text-center fw-light text-secondary">
                <small>Note 2</small>
            </td>
            <td class="text-center fw-light text-secondary">
                <small>Note 3</small>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th scope="col" rowspan="2">Year-Over-Year</th>
            <th scope="col"><?php echo RecordType::ACTUAL; ?> <span class="d-block">($)</span></th>
            <th scope="col"><?php echo RecordType::ACTUAL; ?> <span class="d-block">($)</span></th>
            <th scope="col"><?php echo $previousYearLabel; ?> <span class="d-block">% of Sales</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">% of Sales</span></th>
            <th scope="col">CY - PY <span class="d-block">Difference</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">$ Change</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">% Change</span></th>
            <th scope="col"><?php echo $previousYearLabel; ?> <span class="d-block">$100 Equiv.</span></th>
            <th scope="col"><?php echo $actualYearLabel; ?> <span class="d-block">$100 Equiv.</span></th>
            <th scope="col">CY - PY <span class="d-block">Difference</span></th>
            <th scope="col">Yr-Over-Yr <span class="d-block">Difference</span></th>
        </tr>
    </thead>

    <tbody style="border: 2px solid var(--bs-info);">
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getNetSalesSubset()]); ?>
        <tr>
            <?php echo printThresholdCell(
                $formatter->asDecimal(
                    Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.NET_SALES'), 0
                ),
                'Acceptable level of decline in sales this month over same month last year.',
                7
            ); ?>
        </tr>
    </tbody>
    
    <?php Tools::printEmptyRow(); ?>

    <tbody style="border: 2px solid var(--bs-warning);">
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getDirectCostsSubset()]); ?>

        <tr>
            <?php echo printThresholdCell(
                $formatter->asPercent(
                    Tools::getParam('company.yoy_p&l.thresholds.salesPercentDifference.DIRECT_COSTS'),1
                ),
                'Note 4:  acceptable change in direct costs as a percent of sales this year over last.',
                6
            ); ?>
            <td class="table-primary text-end">
                <?php echo $formatter->asDecimal(
                    Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.DIRECT_COSTS'), 0
                ); ?>
            </td>
        </tr>

        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getCOGSsubset()]); ?>
    </tbody>
    
    <?php Tools::printEmptyRow(); ?>
    
    <tbody>
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getGrossProfitSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>

    <tbody style="border: 2px solid var(--bs-orange);">
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getOperatingCostsSubset()]); ?>
        <tr>
            <?php echo printThresholdCell(
                $formatter->asPercent(
                    Tools::getParam('company.yoy_p&l.thresholds.salesPercentDifference.INDIRECT_COSTS'),1
                ),
                'Note 4:  acceptable change in direct costs as a percent of sales this year over last.',
                6
            ); ?>
            <td class="table-primary text-end">
                <?php echo $formatter->asDecimal(
                    Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.INDIRECT_COSTS'), 0
                ); ?>
            </td>
        </tr>
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getTotalSGnAExpenseSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>

    <tbody>
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getOperatingIncomeSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>

    <tbody style="border: 2px solid var(--bs-secondary);">
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getOthersSubset()]); ?>
        <tr>
            <?php echo printThresholdCell(
                $formatter->asPercent(
                    Tools::getParam('company.yoy_p&l.thresholds.salesPercentDifference.NET_NONOPERATING_COSTS'),1
                ),
                'Note 4:  acceptable change in direct costs as a percent of sales this year over last.',
                6
            ); ?>
            <td class="table-primary text-end">
                <?php echo $formatter->asDecimal(
                    Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.NET_NONOPERATING_COSTS'), 0
                ); ?>
            </td>
        </tr>
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getNetNonOperatingCostsSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>

    <tbody>
        <?php echo $this->render('_yoy_cells', ['recordPairs' => $model->getNetIncomeSubset()]); ?>
    </tbody>
</table>

<div class="mt-5">
    <p class="text-muted small fw-light">
        Note 1:  P&L Statement categories highlighted red indicates a significant drop in performance relative to same month last year.
        <br>
        Note 2:  Green highlighted cells indicate improved performance over last year.  Red indicates a P&L category better managed last year as a percent of sales than this year.
        <br>
        Note 3:  Current year percent of sales minus previous year percent of sales.  Expenses with a positive number indicates the item increased this year over last.  Red highlights each P&L category exceeding the acceptable change amount.
        <br>
        Note 4:  Use the light shaded blue cells to adjust your sales, direct, and indirect cost acceptable change range should you wish to better dial in your ability to quickly see areas of improvement you can celebrate or concern you need to fix.
        <br>
    </p>
</div>

