<?php
/**
 * @var \Tightenco\Collect\Support\Collection $actual
 * @var \Tightenco\Collect\Support\Collection $planned
 * @var string $actualYear
 * @var string $planYear
 * @var Account[] $accounts
 *
 * @var \yii\web\View $this
 */

use app\enums\RecordType;
use app\helpers\F;
use app\models\Account;
use app\models\Record;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$actualYearLabel = $actualYear . ' ' . RecordType::ACTUAL;
$planYearLabel = $planYear . ' ' . RecordType::PLAN;
?>

<?php if ($actual->count() == 0 || $planned->count() == 0): ?>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="text-center">
                Not enough data to display report
            </h5>
        </div>
    </div>
<?php else: ?>

<table class="table table-borderless">
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
        <?php $actualNetSales = $actual->firstWhere('account_id', Account::NET_SALES_ID); ?>
        <?php $plannedNetSales = $planned->firstWhere('account_id', Account::NET_SALES_ID); ?>
        <?php foreach ($accounts as $account): ?>
        <tr class="record" data-account-id="<?php echo $account->id; ?>" data-account-type="<?php echo $account->type == 1 ? 'income' : 'expense'; ?>">
            <?php
            /** @var Record $actualRecord */
            /** @var Record $plannedRecord */
            $actualRecord = $actual->firstWhere('account_id', $account->id);
            $plannedRecord = $planned->firstWhere('account_id', $account->id);
            ?>

            <td class="col-3"><?php echo $account->getDisplayLabel(); ?></td>
            <td class="text-right">
                <?php echo $actualRecord->getValueF(); ?>
            </td>
            <td class="text-right">
                <?php echo $plannedRecord->getValueF(); ?>
            </td>
            
            <?php $actualToPlanPercent = Record::percentageOfActualToPlan($actualRecord, $plannedRecord, false); ?>
            <td class="text-center actual-to-plan" data-value="<?php echo $actualToPlanPercent; ?>">
                <?php echo F::percent($actualToPlanPercent); ?>
            </td>
            
            <td class="text-right">
                <?php echo Record::differenceOfActualToPlan($actualRecord, $plannedRecord); ?>
            </td>
            <td class="text-center">
                <?php echo F::percent($actualToSales = Record::percentageOfActualToPlan($actualRecord, $actualNetSales, false)); ?>
            </td>
            <td class="text-center">
                <?php echo F::percent($planToSales = Record::percentageOfActualToPlan($plannedRecord, $plannedNetSales, false)); ?>
            </td>
            <td class="text-center">
                <?php echo F::percent($actualToSales - $planToSales); ?>
            </td>
            <td class="text-center">
                <?php echo F::equiv100($actualToSales); ?>
            </td>
            <td class="text-center">
                <?php echo F::equiv100($planToSales); ?>
            </td>
            <td class="text-center">
                <?php echo F::equiv100($actualToSales - $planToSales); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

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
                $(this).addClass(`border-bottom-0 border-right-0 border-left-0 border-2 border-info`);
                $('td:first-child', this).addClass('text-info')
                break;
            // Sales Commission
            case 6:
                $(this).addClass(`border-bottom-0 border-right-0 border-left-0 border-2 border-warning`);
                break;
            // COGS
            case 15:
                $(this).addClass(`border-top-0 border-right-0 border-left-0 border-2 border-warning`);
                break;
            // GROSS PROFIT
            case 16:
                $(this).addClass(`border-top-0 border-right-0 border-left-0 border-2`);
                $(this).css({borderColor: 'var(--bs-orange)'});
                $('td:first-child', this).addClass('text-success')
                break;
            case 28:
                $(this).addClass(`border-top-0 border-right-0 border-left-0 border-2`);
                $(this).css({borderColor: 'var(--bs-orange)'});
                break;
            case 29:
                $(this).addClass(`border-top-0 border-right-0 border-left-0 border-2 border-secondary`);
                $('td:first-child', this).addClass('text-success font-weight-bold')
                break;
            case 37:
                $(this).addClass(`border-bottom-0 border-right-0 border-left-0 border-2 border-secondary`);
                $('td:first-child', this).addClass('text-dark font-weight-bold')
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