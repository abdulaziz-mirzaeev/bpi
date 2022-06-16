<?php
/**
 * @var \Tightenco\Collect\Support\Collection $actual
 * @var \Tightenco\Collect\Support\Collection $previous
 * @var int $actualDate
 * @var int $previousDate
 * @var Account[] $accounts
 */

use app\enums\RecordType;
use app\helpers\F;
use app\models\Account;
use app\models\Record;

?>

<?php

$actualDateLabel = $actualDate . ' ' . RecordType::ACTUAL;
$previousDateLabel = $previousDate . ' ' . RecordType::ACTUAL;

$actualYearLabel = Yii::$app->formatter->asDate($actualDate, 'php:Y') . ' ' . RecordType::ACTUAL;
$previousYearLabel = Yii::$app->formatter->asDate($previousDate, 'php:Y') . ' ' . RecordType::ACTUAL;

?>

<?php if ($actual->count() == 0 || $previous->count() == 0): ?>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="text-center">
                Not enough data to display report
            </h5>
        </div>
    </div>

<?php else: ?>

<h2 class="my-4 display-6">R8 Display</h2>

<table class="table table-borderless table-hover">
    <thead class="text-center">
    <tr>
        <th scope="col" rowspan="2">Year-Over-Year</th>
        <th scope="col"><?php echo $previousDateLabel; ?> <span class="d-block">($)</span></th>
        <th scope="col"><?php echo $actualDateLabel; ?> <span class="d-block">($)</span></th>
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
    <tbody>
    <?php $actualNetSales = $actual->firstWhere('account_id', Account::NET_SALES_ID); ?>
    <?php $previousNetSales = $previous->firstWhere('account_id', Account::NET_SALES_ID); ?>
    <?php $formatter = Yii::$app->formatter; ?>
    <?php foreach ($accounts as $account): ?>
        <tr class="record" data-account-id="<?php echo $account->id; ?>" data-account-type="<?php echo $account->type == 1 ? 'income' : 'expense'; ?>">
            <?php
            /** @var Record $actualRecord */
            /** @var Record $previousRecord */
            $actualRecord = $actual->firstWhere('account_id', $account->id);
            $previousRecord = $previous->firstWhere('account_id', $account->id);
            ?>

            <td class="col-3"><?php echo $account->getDisplayLabel(); ?></td>
            <td class="text-right"><?php echo $previousRecord->getValueF(); ?></td>
            <td class="text-right"><?php echo $actualRecord->getValueF(); ?></td>
            <td class="text-center">
                <?php echo F::percent($previousToSalesPercent = Record::percentageOfActualToPlan($previousRecord, $previousNetSales, false)); ?>
            </td>
            <td class="text-center">
                <?php echo F::percent($actualToSalesPercent = Record::percentageOfActualToPlan($actualRecord, $actualNetSales, false)); ?>
            </td>
            <td class="text-center">
                <?php echo F::percent($actualToSalesPercent - $previousToSalesPercent, 1); ?>
            </td>
            <td class="text-right">
                <?php echo F::decimal($changePercent = $actualRecord->value - $previousRecord->value) ?>
            </td>
            <td class="text-center">
                <?php echo F::percent($changeP = Record::percentageOfXtoY($changePercent, $previousRecord->value)) ?>
            </td>
            <td class="text-center">
                <?php echo F::equiv100($previousToSalesPercent); ?>
            </td>
            <td class="text-center">
                <?php echo F::equiv100($actualToSalesPercent); ?>
            </td>
            <td class="text-center">
                <?php echo $difference = F::equiv100($actualToSalesPercent - $previousToSalesPercent); ?>
            </td>
            <td class="text-center">
                <?php
                if (in_array($account->id, [
                        Account::NET_SALES_ID, Account::GROSS_PROFIT_ID, Account::OPERATING_INCOME_ID, Account::NET_INCOME_ID
                ])) {
                    if ($account->id == Account::NET_SALES_ID) {
                        echo $changeP > 0 ? 'better' : 'worse';
                    } else {
                        echo $difference > 0 ? 'better' : 'worse';
                    }
                } else {
                    echo $difference > 0 ? 'more' : 'less';
                }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>