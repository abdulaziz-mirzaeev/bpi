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
use app\helpers\Tools;
use app\models\Account;
use app\models\Record;

$formatter = Yii::$app->formatter;
$date = $formatter->asDate($model->date, 'php:Y');

$actualYearLabel = $date . ' ' . RecordType::ACTUAL;
$planYearLabel = $date . ' ' . RecordType::PLAN;

function printThresholdRow($value, $message): string {
    $valueF = Yii::$app->formatter->asDecimal($value, 0);
    return <<<HTML
        <tr>
            <td colspan="4" class="fw-light text-muted text-end">
                <small>$message</small>
            </td>
            <td style="background-color: lightyellow;" class="text-end">
                $valueF
            </td>
        </tr>
    HTML;
}
?>

<h2 class="text-center"><?php echo Yii::$app->params['company']['name'] ?? 'Dybacco Constructions'; ?></h2>
<h2 class="mb-4 text-center"><?php echo $formatter->asDate($model->date, 'php:M-Y'); ?></h2>
<h2 class="mb-4 display-6 text-center">Actual-to-Plan P&L Table</h2>
<p class="text-center">Below are your actual-to-plan monthly results to appreciate where in your business you did better and worse than planned.</p>

<table class="table table-borderless">
    <thead class="text-center">
        <tr>
            <td class="text-center fw-light text-secondary">
                <small>Note 1</small>
            </td>
            <td>
                <?php echo $formatter->asDate($model->date, 'php:M-y'); ?>
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
            <td class="text-center fw-light text-secondary">
                <small>Note 4</small>
            </td>
            <td class="text-center fw-light text-secondary">
                <small>Note 5</small>
            </td>
            <td class="text-center fw-light text-secondary">
                <small>Note 6</small>
            </td>
            <td class="text-center fw-light text-secondary">
                <small>Note 7</small>
            </td>
        </tr>
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

    <?php Tools::printRowMessage('Net Sales are significantly better than plan.  The question is why? '); ?>

    <tbody style="border: 2px solid var(--bs-info);">
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getNetSalesSubset()]); ?>
        <?php echo printThresholdRow(
            Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.NET_SALES'),
            'Actual sales compared to plan threshold indicating a significant issue.'
        ); ?>
    </tbody>
    
    <?php Tools::printEmptyRow(); ?>
    <?php Tools::printRowMessage('COGS came in significantly more than planned leading to Gross Profit of  $161,918. he red shaded cells to the right show you what is driving your upcoming cash flow pressures. '); ?>

    <tbody style="border: 2px solid var(--bs-warning);">
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getDirectCostsSubset()]); ?>
        <?php echo printThresholdRow(
            Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.DIRECT_COSTS'),
            'Actual direct expenses greater than plan threshold indicating a significant issue.'
        ); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getCOGSsubset()]); ?>
    </tbody>
    
    <?php Tools::printEmptyRow(); ?>

    <?php
    $message = "Exceeding your sales plan by $544,506 while missing your Gross Profit plan by -$178,205 is a serious business problem.  Generating negative Gross Profit is both the surest and fastest way to going out of business.  Your business is not sustainable on $161,918 in Gross Profit.  You either need to immediately determine how you get closer to a Gross Margin of  31% or stop selling so much";
    Tools::printRowMessage($message);
    ?>
    
    <tbody>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getGrossProfitSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php Tools::printRowMessage('You spent more than planned on indirect costs contributing to your Operating Income problems.  The red shaded cells to the right show you where this is happening.'); ?>
    
    <tbody style="border: 2px solid var(--bs-orange);">
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getOperatingCostsSubset()]); ?>
        <?php echo printThresholdRow(
            Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.INDIRECT_COSTS'),
            'Actual indirect expenses greater than plan threshold indicating a significant issue.'
        ); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getTotalSGnAExpenseSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php Tools::printRowMessage('Missing your sales goal by  49%  has led to your being  48%  of your Gross Profit goal resulting in  negative Operating Income of -$271,047'); ?>

    <tbody>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getOperatingIncomeSubset()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php Tools::printRowMessage('There is no significant actual-to-plan nonoperating cost variances.'); ?>

    <tbody style="border: 2px solid var(--bs-secondary);">
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getOthersSubset()]); ?>
        <?php echo printThresholdRow(
            Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.NET_NONOPERATING_COSTS'),
            'Actual nonoperating expenses greater than plan threshold indicating a significant issue.'
        ); ?>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getNetNonOperatingCosts()]); ?>
    </tbody>

    <?php Tools::printEmptyRow(); ?>
    <?php Tools::printRowMessage('The Gross Profit miss of  -$178,205 has put you in a hole that you aren\'t digging out of leading to your Net Income miss of  -$280,635'); ?>

    <tbody>
        <?php echo $this->render('_r7_cells', ['recordPairs' => $model->getNetIncomeSubset()]); ?>
    </tbody>
</table>

<div class="mt-5">
    <p class="text-muted small fw-light">
        Where expense results are green you are better than plan, orange is close to plan, red is above plan, and purple is questionably better than planned.
        <br>
        Note 1:  Names of major account groupings reflected on your P&L Statement comprising direct costs, overhead expenses, and nonoperating costs.
        <br>
        Note 2:  Your actual to planned results as a percent of sales.  Over 100% is higher than planned, below 100% is below plan
        <br>
        Note 3:  Total dollar difference of actual minus planned results for the month.  A negative number associated with expenses reflects a total spending below plan.
        <br>
        Note 4:  Percent difference of actual minus planned as a percent of sales.  A positive expense percent indicates spending more than planned.
        <br>
        Note 5:  Shows you how much of every $100 you collect in sales is spent to generate $100 in sales.  The amount reflected in the Net Income row reflects how much is actually held onto.
        <br>
        Note 6:  Shows you how much of every $100 you collect in sales you planned to spend.  The amount reflected in the Net Income row reflects how much you planned to earn on every $100 collected.
        <br>
        Note 7:  Shows the difference between what was actually spent and what you planned to spend for every $100 in sales.  A positive Net Income number indicates you held onto more than planned
        <br>
        Every item shaded red reflects a problem area for your business.  It indicates that you either sold less than planned or spent more than planned.  Correct immediately or miss your profit plan
        <br>
        Every item shaded green reflects an area for your business performing acceptably better than  planned.  Purple shaded areas indicate an area that performed significantly better than plan.
    </p>
</div>

