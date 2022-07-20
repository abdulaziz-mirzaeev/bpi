<?php


namespace app\models;


use app\enums\AccountId;
use app\enums\AccountStatement;
use app\enums\RecordType;
use app\enums\ScoreCodes;
use Yii;

class ReportPL_VR1 extends ReportA2P
{
    public $recordPairClass = RecordPairPL_VR1::class;

    public float $lowOfPlan = 0.95;
    public float $highOfPlan = 1.05;

    public function getDirectCostCogsSubset()
    {
        return [...$this->getDirectCostsSubset(), ...$this->getCOGSsubset()];
    }

    public function getOperatingCostsAndSGA()
    {
        return [...$this->getOperatingCostsSubset(), ...$this->getTotalSGnAExpenseSubset()];
    }

    public function getLowOfPlan()
    {
        return Yii::$app->formatter->asPercent($this->lowOfPlan);
    }

    public function getHighOfPlan()
    {
        return Yii::$app->formatter->asPercent($this->highOfPlan);
    }

    public function getPlanGrossMarginPercentage($formatting = true, $decimals = 0)
    {
        $value = $this->comparable->getGrossProfit()->value / $this->comparable->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function getActualGrossMarginPercentage($formatting = true, $decimals = 0)
    {
        $value = $this->actual->getGrossProfit()->value / $this->actual->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function getPlanOperatingIncomePercentage($formatting = true, $decimals = 0)
    {
        $value = $this->comparable->getOperatingIncome()->value / $this->comparable->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function getActualOperatingIncomePercentage($formatting = true, $decimals = 0)
    {
        $value = $this->actual->getOperatingIncome()->value / $this->actual->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function getPlanReturnOnSales($formatting = true, $decimals = 0)
    {
        $value = $this->comparable->getNetIncome()->value / $this->comparable->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function getActualReturnOnSales($formatting = true, $decimals = 0)
    {
        $value = $this->actual->getNetIncome()->value / $this->actual->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function actualNetSalesToPlanScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NS_ACTUAL_TO_PLAN)->value;
        $dollarDifferenceA2P = '$'. $this->getRecordByAccount(AccountId::NET_SALES)->dollarDifferenceA2P();

        $messages = [
            'Net Sales are significantly better than plan.  The question is why?',
            'Net Sales are above plan by ' . $dollarDifferenceA2P,
            'Net Sales are close to plan by ' . $dollarDifferenceA2P,
            'Net Sales are below plan by ' . $dollarDifferenceA2P,
            'Something seriously went wrong with sales this month?',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function netSalesScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NET_SALES_SCORE)->value;

        $messages = [
            'Your sales success will give you more cash inflow to cover your costs at a profit.',
            'Your sales success will give you more cash inflow to cover your costs at a profit.',
            'You operations success more than sales success led to you earning a profit.',
            'Your sales are failing to cover your direct costs and overhead at a profit.',
            'Your business cannot survive when your operating costs are higher than your sales.',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function grossProfitActualToPlanScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::GP_ACTUAL_TO_PLAN_PERCENT)->value;

        $messages = [
            'Results like this = No need for corrective action in your operations',
            'Better than planned Gross Profit = Solid operations management',
            'Nothing to get excited or worried about this month relative to your operations\'',
            'Lower sales normally lead to Gross Profit challenges like they did this month',
            'Failure to generate sufficient sales is your #1 Gross Profit problem',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function grossProfitScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::GP_SCORE)->value;

        $messages = [
            'The key to making more money starts with exceeding your Net Sales plan followed by beating your Gross Profit plan as you did this month.',
            'It\'s a good month anytime you earn more in Gross Profit than planned as you did this month.',
            'Being close on your Gross Profit plan means your business performed as you expected it to perform.',
            'The problem with missing your Gross Profit plan is you now have less in first level profits to cover your overhead costs.',
            'A low to negative G.P. puts you in an immediate cash flow hole that is an "all hands on deck" problem to solve before more money is lost.',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function OIScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::OI_SCORE)->value;

        $messages = [
            'You carried your direct operations success relative to your profit plan management through your admin and finance functions.',
            'You do a great job managing your overhead costs this month as evidenced by the amount of Operating Income generated.',
            'Your Operating Income came in close to where you planned it to be this month.',
            'You will never make money when you generate insufficient G.P. unless those you pay your overhead expenses to refund you the money you have paid them.',
            'Insufficient G.P. leads to unacceptable O.I. unless those you pay your overhead expenses to refund you the money you have paid them.',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function OIActualToPlanScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::OI_ACTUAL_TO_PLAN_PERCENT)->value;

        $messages = [
            'Results like this = No need for corrective action',
            'Better than planned results = Solid expense management',
            'Nothing to get excited or worried about this month',
            'Gross Profit issues always lead to Operating Income challenges',
            'Failure to generate sufficient sales is your #1 operating problem',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function netIncomeActualToPlanScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NI_ACTUAL_TO_PLAN_PERCENT)->value;

        $messages = [
            'Earning a WOW on your Net Income plan = cause for celebration!',
            'Better than planned results = Exceptional expense management.',
            'Your bottom-line is nothing to get excited or worried about.',
            'Your Net Income is low because you had operating profit challenges.',
            'Failure to generate sufficient sales is your #1 business problem',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function netIncomeScoreInterpretation()
    {
        $score = ActualToPlanScore::getByDateAndCode($this->date, ScoreCodes::NI_SCORE)->value;

        $messages = [
            'This was an exceptional month.  It is time to pause as a management team to identify exactly what went well this month so you can repeat it.',
            'Well done.  Anytime you are close to your Net Income goal is an accomplishment worth celebrating.',
            'Your return on sales reflected in your Net Income is nothing to celebrate this month.',
            'You do a great job managing your overhead costs Your Net Income problem is a function of not generating enough Gross Profit.',
            'You had a very difficult month that has put you in a financial hole by spending more than you sold is always a serious problem to fix.',
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }
}