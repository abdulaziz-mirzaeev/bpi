<?php
/**
 * @var \Tightenco\Collect\Support\Collection $actual
 * @var \Tightenco\Collect\Support\Collection $planned
 * @var string $actualYear
 * @var string $planYear
 * @var Account[] $accounts
 * @var integer $reportType
 * @var array $display_r7
 * @var array $display_r8
 *
 * @var \yii\web\View $this
 */

use app\enums\RecordType;
use app\helpers\F;
use app\models\Account;
use app\models\Record;
use app\models\ReportForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<h2 class="text-center">Dybaco Construction</h2>
<h4 class="text-center fw-light text-uppercase">BPI Scoreboards</h4>
<p>
    Highly profitable business owners with sustainable cash reserves set aside time each month to review their business results.
    They know that their profit plan is their best measurement for success.
    They use it as a basis for comparison to help them confirm what's working well in their business
    and what they need to fix to make more money.
</p>

<?php echo $this->render('_form', ['model' => $model]); ?>


<?php
if (isset($reportType)) {
    if ($reportType == ReportForm::REPORT_R7) {
        echo $this->render('display_r7', $display_r7);
    }

    if ($reportType == ReportForm::REPORT_R8) {
        echo $this->render('display_r8', $display_r8);
    }
}
?>
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