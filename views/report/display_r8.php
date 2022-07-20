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
use yii\helpers\Url;

?>

<?php

$actualDateLabel = $actualDate . ' ' . RecordType::ACTUAL;
$previousDateLabel = $previousDate . ' ' . RecordType::ACTUAL;

$actualYearLabel = Yii::$app->formatter->asDate($actualDate, 'php:Y') . ' ' . RecordType::ACTUAL;
$previousYearLabel = Yii::$app->formatter->asDate($previousDate, 'php:Y') . ' ' . RecordType::ACTUAL;

?>

<a class="btn btn-primary" href="<?php echo Url::to(Yii::$app->request->referrer); ?>">< Go Back</a>
<h2 class="my-4 display-6 text-center">R8 Display</h2>

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

<?php $this->registerJs(<<<JS
    $('tr.record').each(function () {
        let accountType = $(this).data('account-type');
        let accountId = $(this).data('account-id');

        let redStyle = {backgroundColor: 'var(--bs-danger)', color: '#fff'};
        let purpleStyle = {backgroundColor: 'var(--bs-purple)', color: '#fff'};
        let yellowStyle = {backgroundColor: 'var(--bs-warning)', color: '#000'};
        let greenStyle = {backgroundColor: 'var(--bs-success)', color: '#fff'};
            
        let _this = $('td.actual-to-plan', this);
        let cellValue = $(_this).data('value');
        
        switch (accountId) {
            // NET SALES
            case 5: 
                $(this).css({'borderTop': '2px solid var(--bs-info)'})
                $('td:first-child', this).addClass('text-info fw-bold')
                break;
            // Sales Commission
            case 6:
                $(this).css({'borderTop': '2px solid var(--bs-warning)'})
                break;
            // COGS
            case 15:
                $(this).css({'borderBottom': '2px solid var(--bs-warning)'})
                break;
            // GROSS PROFIT
            case 16:
                $(this).css({'borderBottom': '2px solid var(--bs-orange)'})
                $('td:first-child', this).addClass('text-success fw-bold')
                break;
            case 28:
                $(this).css({'borderBottom': '2px solid var(--bs-orange)'})
                break;
            case 29:
                $(this).css({'borderBottom': '2px solid var(--bs-secondary)'})
                $('td:first-child', this).addClass('text-success fw-bold');
                break;
            case 37:
                $(this).css({'borderTop': '2px solid var(--bs-secondary)'})
                $('td:first-child', this).addClass('text-dark fw-bold')
                break;
        }
        
        if (accountType === 'expense') {
            if (cellValue < 0.8 ) {
                $(_this).css(purpleStyle);        
            } else if (cellValue >= 0.8 && cellValue < 0.95 ) {
                $(_this).css(yellowStyle);
            } else if (cellValue >= 0.95 && cellValue < 1.05) {
                $(_this).css(greenStyle);
            } else if (cellValue >= 1.05) {
                $(_this).css(redStyle);
            }
        } 
        
        if (accountType === 'income') {
            if (cellValue < 0.8 ) {
                $(_this).css(redStyle);        
            } else if (cellValue >= 0.8 && cellValue < 0.95 ) {
                $(_this).css(yellowStyle);
            } else if (cellValue >= 0.95 && cellValue < 1.1) {
                $(_this).css(greenStyle);
            } else if (cellValue >= 1.1) {
                $(_this).css(purpleStyle);
            }
        }
    });
JS

) ?>
