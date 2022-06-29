<?php
/**
 * @var \app\models\RecordPair[] $recordPairs
 */

use app\enums\AccountId;

?>

<?php foreach ($recordPairs as $recordGroup): ?>
    <?php
    $accountClass = '';
    $accountId = $recordGroup->account->id;
    switch ($accountId) {
        case AccountId::NET_SALES:
            $accountClass = 'fw-bold text-info';
            break;
        case AccountId::GROSS_PROFIT:
        case AccountId::OPERATING_INCOME:
            $accountClass = 'fw-bold text-success';
            break;
        case AccountId::NET_INCOME:
            $accountClass = 'fw-bold text-dark';
            break;
    }
    ?>
    <tr>
        <td class="<?php echo $accountClass; ?>"><?php echo $recordGroup->account->getDisplayLabel(); ?></td>
        <td class="text-end"><?php echo $recordGroup->actual->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->plan->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->percentageA2P(); ?></td>
        <td class="text-end"><?php echo $recordGroup->dollarDifferenceA2P(); ?></td>
        <td class="text-end"><?php echo $recordGroup->percentageA2NetSales(); ?></td>
        <td class="text-end"><?php echo $recordGroup->percentageP2NetSales(); ?></td>
        <td class="text-end"><?php echo $recordGroup->percentNetSalesDifferenceA2P(); ?></td>
        <td class="text-end"><?php echo $recordGroup->actualNetSalesEquiv100(); ?></td>
        <td class="text-end"><?php echo $recordGroup->planNetSalesEquiv100(); ?></td>
        <td class="text-end"><?php echo $recordGroup->equiv100DifferenceA2P(); ?></td>
    </tr>
<?php endforeach; ?>
