<?php
/**
 * @var \app\models\ReportPL_VR2 $model
 * @var \yii\web\View $this
 * @var string $name
 * @var string $subsetName
 */

use app\enums\RecordType;

$date = Yii::$app->formatter->asDate($model->date, 'php:Y');
$datePrevious = Yii::$app->formatter->asDate($model->datePrevious, 'php:Y');

$actualYearLabel = RecordType::ACTUAL;
$planYearLabel = RecordType::ACTUAL;

$currentDateMonth = Yii::$app->formatter->asDate($model->date, 'php:M-y');
$previousDateMonth = Yii::$app->formatter->asDate($model->datePrevious, 'php:M-y');
?>

<tbody class="fw-light">
    <tr class="text-center" >
        <td class="align-bottom"></td>
        <td class="align-bottom"><?php echo $currentDateMonth; ?></td>
        <td class="align-bottom"><?php echo $previousDateMonth; ?></td>
        <td class="align-bottom"><?php echo $subsetName ?? ''; ?></td>
        <td class="align-bottom"><?php echo $currentDateMonth; ?></td>
        <td class="align-bottom">Prev YR</td>
        <td class="align-bottom">Curr YR</td>
        <td class="align-bottom">Add'l Spend</td>
        <td class="align-bottom text-nowrap" colspan="2">L/H % of Sales Range</td>
        <td class="align-bottom">% of Sales</td>
        <td class="align-bottom"></td>
    </tr>
    <tr class="border-bottom border-1 border-dark">
        <th scope="row"><?php echo $name; ?></th>
        <td class="fw-light text-nowrap"><?php echo $datePrevious. ' ' . $actualYearLabel; ?></td>
        <td class="fw-light text-nowrap"><?php echo $date . ' ' . $planYearLabel; ?></td>
        <td class="fw-light text-nowrap">$ Change</td>
        <td class="fw-light text-nowrap">% Change</td>
        <td class="fw-light text-nowrap">% Sales</td>
        <td class="fw-light text-nowrap">% Sales</td>
        <td class="fw-light text-nowrap">% Sales</td>
        <td class="table-primary text-end"><?php echo $model->getLowOfSalesRange(); ?></td>
        <td class="table-primary text-end"><?php echo $model->getHighOfSalesRange(); ?></td>
        <td class="text-center text-nowrap">Low/High</td>
        <td class="text-center text-nowrap">Area to Act</td>
    </tr>
</tbody>
