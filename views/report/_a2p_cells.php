<?php
/**
 * @var \app\models\RecordPairA2P[] $recordPairs
 */

use app\enums\AccountId;

?>

<?php foreach ($recordPairs as $recordGroup): ?>
    <?php

    ?>
    <tr>
        <td class="<?php echo $recordGroup->getAccountClass(); ?> text-nowrap">
            <?php echo $recordGroup->account->getDisplayLabel(); ?>
        </td>
        <td class="text-end"><?php echo $recordGroup->actual->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->plan->valueF; ?></td>
        <td class="text-center" style="<?php echo $recordGroup->percentageA2PStyle(); ?>">
            <?php echo $recordGroup->percentageA2P(); ?>
        </td>
        <td class="text-end" style="<?php echo $recordGroup->dollarDifferenceA2PStyle(); ?>">
            <?php echo $recordGroup->dollarDifferenceA2P(); ?>
        </td>
        <td class="text-end"><?php echo $recordGroup->percentageA2NetSales(); ?></td>
        <td class="text-end"><?php echo $recordGroup->percentageP2NetSales(); ?></td>
        <td class="text-end"><?php echo $recordGroup->percentNetSalesDifferenceA2P(); ?></td>
        <td class="text-end"><?php echo $recordGroup->actualNetSalesEquiv100(); ?></td>
        <td class="text-end"><?php echo $recordGroup->planNetSalesEquiv100(); ?></td>
        <td class="text-end"><?php echo $recordGroup->equiv100DifferenceA2P(); ?></td>
    </tr>
<?php endforeach; ?>
