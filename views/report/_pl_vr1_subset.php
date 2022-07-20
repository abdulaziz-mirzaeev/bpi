<?php
/**
 * @var \app\models\RecordPairPL_VR1[] $recordPairs
 */

use app\enums\AccountId;
use yii\bootstrap5\Html;

?>

<?php foreach ($recordPairs as $recordGroup): ?>
    <?php $accountId = $recordGroup->account->id; ?>
    <tr data-account-id="<?php echo $accountId; ?>">
        <td class="<?php echo $recordGroup->account->getClass(); ?>text-nowrap">
            <?php echo $recordGroup->account->name; ?>
        </td>
        <td class="text-end"><?php echo $recordGroup->plan->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->actual->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->percentageP2NetSales(true, 1); ?></td>

        <td class="text-end" style="<?php echo $recordGroup->getA2NetSalesStyle(); ?>">
            <?php echo $recordGroup->percentageA2NetSales(true, 1); ?>
        </td>

        <td class="text-end"><?php echo $recordGroup->dollarDifferenceA2P(); ?></td>
        <td class="text-center"><?php echo $recordGroup->percentageA2P(true, 1);?></td>

        <?php if ($accountId == AccountId::NET_SALES): ?>
            <td class="text-end"><?php echo $recordGroup->model->getLowOfPlan(); ?></td>
            <td class="text-end"><?php echo $recordGroup->model->getHighOfPlan(); ?></td>
            <td class="text-center"><?php echo $recordGroup->inOrOutOfPlan(); ?></td>
        <?php else: ?>
            <td class="text-end"><?php echo $recordGroup->getLowPercentageOfPlan(true, 1); ?></td>
            <td class="text-end"><?php echo $recordGroup->getHighPercentageOfPlan(true, 1); ?></td>
            <td class="text-center" style="<?php echo $recordGroup->getPercentOfSalesAlignmentStyle(); ?>">
                <?php echo $recordGroup->getPercentOfSalesAlignment(); ?>
            </td>
        <?php endif; ?>

        <td class="text-center">
            <?php echo Html::checkbox("areaToAct[$accountId]", false, [
                    'class' => 'form-check-input area-to-act',
                    'data-account-id' => $accountId,
            ]); ?>
        </td>
    </tr>
<?php endforeach; ?>
