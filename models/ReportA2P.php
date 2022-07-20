<?php


namespace app\models;


use app\enums\AccountId;
use app\enums\AccountStatement;
use app\enums\ScoreCodes;
use app\enums\RecordType;
use app\helpers\Tools;
use Yii;

class ReportA2P extends ReportPL
{
    public $recordPairClass = RecordPairA2P::class;

    public function __construct($date)
    {
        $this->date = date('Y-m', strtotime($date));

        $this->actual = new Dataset($this->date, RecordType::ACTUAL);
        $this->comparable = new Dataset($this->date, RecordType::PLAN);
    }

    public function getNetSalesInterpretation(): string
    {
        $formatter = Yii::$app->formatter;
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NET_SALES_SCORE)->value;
        $dollarDifferenceA2P = '$' .
            $this->getRecordByAccount(AccountId::NET_SALES)->dollarDifferenceA2P();


        $messages = [
            'Net Sales are significantly better than plan.  The question is why?',
            'Net Sales are above plan by ' . $dollarDifferenceA2P,
            'Net Sales are close to plan by ' . $dollarDifferenceA2P,
            'Net Sales are below plan by ' . $dollarDifferenceA2P,
            'Something seriously went wrong with sales this month?',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function getCogsInterpretation(): string
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date,
            ScoreCodes::COGS_ACTUAL_TO_PLAN_PERCENT)->value;

        $gpDollarDiff = '$' . $this->getRecordByAccount(AccountId::GROSS_PROFIT)->dollarDifferenceA2P();
        $grossProfit = '$' . $this->getRecordByAccount(AccountId::GROSS_PROFIT)->actual->getValueF();

        $messages = [
            'The purple Cost of Goods Sold Cells indicate where you are significantly better than plan leading you to beat your Gross Profit goal by ' . $gpDollarDiff,
            "And your direct costs were managed below plan leading you to earn $gpDollarDiff less in Gross Profit.",
            "And there is no significant actual-to-plan direct cost variances.",
            "You spent more than planned on direct costs by $gpDollarDiff The red shaded cells to the right show you where this happened.",
            "COGS came in significantly more than planned leading to Gross Profit of  $grossProfit.   The red shaded cells to the right show you what is driving your upcoming cash flow pressures."
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function getOperatingCostsInterpretation(): string
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date,
            ScoreCodes::SG_AND_A_ACTUAL_TO_SALES_PERCENT)->value;

        $messages = [
            'The purple overhead cost cells indicate where you are significantly better than plan.',
            'While you managed your overhead costs to plan.',
            'There is no significant actual-to-plan indirect cost variances.',
            'You spent more than planned on indirect costs contributing to your Operating Income problems.  The red shaded cells to the right show you where this is happening.',
            'SG&A came in significantly more than planned leading to a negative Operating Income.  The red shaded cells to the right shows what is behind your upcoming cash flow pressures.',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function getOperatingIncomeInterpretation(): string
    {
        $scoreA = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::GP_ACTUAL_TO_PLAN_PERCENT);
        $scoreB = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::OI_ACTUAL_TO_PLAN_PERCENT);

        $score = $scoreA->value + $scoreB->value;

        $intervals = [
            [15, 20],
            [7, 14],
            [-6, 6],
            [-14, -7],
            [-20, -15],
        ];

        $operatingIncomeDollarDifference = '$' . $this->getRecordByAccount(AccountId::OPERATING_INCOME)->dollarDifferenceA2P();
        $grossProfitDollarDifference = '$' . $this->getRecordByAccount(AccountId::GROSS_PROFIT)->dollarDifferenceA2P();

        $netSalesPercentageToPlan = $this->getRecordByAccount(AccountId::NET_SALES)->percentageA2P(false);
        $grossProfitPercentageToPlan = $this->getRecordByAccount(AccountId::GROSS_PROFIT)->percentageA2P();
        $netSalesToPlanMiss = Yii::$app->formatter->asPercent($netSalesPercentageToPlan - 1);

        $messages = [
            "Your Gross Profit success led you to earning {$operatingIncomeDollarDifference} more in Operating Income than planned.",
            "Your Gross Profit success led you to earning {$operatingIncomeDollarDifference} more in Operating Income than planned.",
            "Your Gross Profit shortfall plus your overhead management overspend led to you earning less in Operating Income than planned.",
            "Your Gross Profit miss of {$grossProfitDollarDifference} led you to falling short of your Operating Income plan by {$operatingIncomeDollarDifference}",
            "Missing your sales goal by  {$netSalesToPlanMiss}  has led to your being  {$grossProfitPercentageToPlan}  of your Gross Profit goal resulting in  negative Operating Income of {$operatingIncomeDollarDifference}",
        ];

        return $this->conditionalMessageByScore($score, $messages, $intervals);
    }

    public function getNonoperatingCostsInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NONOP_ACTUAL_TO_PLAN_PERCENT)->value;

        $messages = [
            "The purple nonoperating cost cells indicate where you are significantly better than plan.",
            "While you managed your nonoperating costs to plan.",
            "There is no significant actual-to-plan nonoperating cost variances.",
            "The red shaded cells to the right shows where you overspent on your nonoperating costs.",
            "The red shaded cells to the right shows where you overspent on your  nonoperating costs.",
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function getNetIncomeInterpretation(): string
    {
        $scoreA = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NI_ACTUAL_TO_PLAN_PERCENT)->value;
        $scoreB = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::OI_ACTUAL_TO_PLAN_PERCENT)->value;

        $score = $scoreA + $scoreB;

        $netIncomeDollarDifference = $this->getRecordByAccount(AccountId::NET_INCOME)->dollarDifferenceA2P();
        $operatingIncomeDollarDifference = $this->getRecordByAccount(AccountId::OPERATING_INCOME)->dollarDifferenceA2P();
        $grossProfitDollarDifference = $this->getRecordByAccount(AccountId::GROSS_PROFIT)->dollarDifferenceA2P();

        $intervals = [
            [15, 20],
            [7, 14],
            [-6, 6],
            [-14, -7],
            [-20, -15],
        ];

        $messages = [
            "Your Operating Income success led you to earning  {$netIncomeDollarDifference} more in Net Income than planned.",
            "Your Operating Income success led you to earning {$netIncomeDollarDifference} more in Net Income than planned.",
            "Your Operating Income shortfall plus high expenses led to you earning less in Net Income than planned.",
            "Your Operating Income miss of {$operatingIncomeDollarDifference}   led you to falling short of your Net Income plan by  {$netIncomeDollarDifference}",
            "The Gross Profit miss of  {$grossProfitDollarDifference} has put you in a hole that you aren't digging out of leading to your Net Income miss of  {$netIncomeDollarDifference}",
        ];

        return $this->conditionalMessageByScore($score, $messages, $intervals);
    }
}