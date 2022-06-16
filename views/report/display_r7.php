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

<h2 class="my-4 display-6">R7 Display</h2>

<?php if ($actual->count() == 0 || $planned->count() == 0): ?>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="text-center">
                Not enough data to display report
            </h5>
        </div>
    </div>
<?php else: ?>

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