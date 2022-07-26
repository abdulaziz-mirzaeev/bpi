<?php


namespace app\models;


use app\enums\AccountId;
use app\enums\AccountStatement;
use app\enums\ScoreCodes;
use Yii;

class ReportPL_VR2 extends ReportYOY
{
    public $recordPairClass = RecordPairPL_VR2::class;

    public $lowOfChange = 0.95;
    public $highOfChange = 1.05;

    public $lowOfSalesRange = 0.8;
    public $highOfSalesRange = 0.9;

    public function getLowOfChange($formatting = true)
    {
        return $formatting ? Yii::$app->formatter->asPercent($this->lowOfChange) : $this->lowOfChange;
    }

    public function getHighOfChange($formatting = true)
    {
        return $formatting ? Yii::$app->formatter->asPercent($this->highOfChange) : $this->highOfChange;
    }

    public function getLowOfSalesRange($formatting = true)
    {
        return $formatting ? Yii::$app->formatter->asPercent($this->lowOfSalesRange) : $this->lowOfSalesRange;
    }

    public function getHighOfSalesRange($formatting = true)
    {
        return $formatting ? Yii::$app->formatter->asPercent($this->highOfSalesRange) : $this->highOfSalesRange;
    }

    public function getMarginsByAccountId($id)
    {
        $previousYear = $this->getPreviousYearMarginByAccountId($id);
        $currentYear = $this->getCurrentYearMarginByAccountId($id);
        $actualChangeInDollars = $this->getActualChangeInDollarsMarginByAccountId($id);

        return [$previousYear, $currentYear, $actualChangeInDollars];
    }

    public function getPreviousYearMarginByAccountId($id, $formatting = true, $decimal = 0)
    {
        return $this->getRecordByAccount($id)->percentagePreviousToSales($formatting, $decimal);
    }

    public function getCurrentYearMarginByAccountId($id, $formatting = true, $decimals = 0)
    {
        return $this->getRecordByAccount($id)->percentageActualToSales($formatting, $decimals);
    }

    public function getActualChangeInDollarsMarginByAccountId($id, $formatting = true, $decimals = 0)
    {
        return $this->getRecordByAccount($id)->additionalSpendOfSalesInPercent($formatting, $decimals);
    }

    public function interpretationNS_1_1()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::NS_DOLLAR_CHANGE)->value;

        $messages = [
            'Congratulations for significantly higher sales!',
            'Sales are up this year over last.',
            'Insignificant year-over-year change in sales.',
            'Sold less this year over last.',
            'Sales are way below last year.  Is this cause for concern?'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationNS_1_0()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::NET_SALES_SCORE)->value;

        $messages = [
            'Your sales volume drives your profit power.  Knowing whether your sales are going to be up or down impacts the quality of your profits by allowing you to proactively, not reactively adjust operations.',
            'When SG&A is low, profit power lies in operations management regardless of whether sales are up or down year-over-year.  I.e., the higher the Gross Profit the more money drops to the bottom-line.',
            'No matter what the sales volume, a well run operations can generate Gross Profit to cover business overhead costs.',
            'When your direct costs consume the majority of your first level profits you will have bottom-line profit problems no matter what sales are.  Your Operations needs to do better converting sales to profits.',
            'When sales are low you must be careful that direct costs do not consume the majority of sales or you will have bottom-line profit problems no matter what sales are.'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationGP_2_1()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::GP_2_1)->value;

        $messages = [
            'You had a significantly better month this year over this time last year.',
            'Congratulations!  You made good money this year on less sales.',
            'You failed to make good money this month as reflected in your total Gross Profit dollars.',
            'This time last year was better than what you experienced this year.',
            'This time last year was a significantly better month than what you experienced this year.'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationGP_2_0()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::GP_SCORE)->value;

        $messages = [
            'Achieving this amount of Gross Profit in terms of dollars and as a percent of sales is an accomplishment.  The immediate opportunity is to identify what worked well for you to build on in the months ahead.',
            'This is a good month to identify what areas of your business cost you less and what areas more money this month over the same month last year.',
            'Year-over-year spend as a percent of sales robbed you of profits.  When sales decrease like this the failure of decreasing direct costs by a similar amount as a percent of sales impacts profits.',
            'The goal this month is to learn what you could have done better to improve your profitability as a percent of sales.  Particularly considering how much higher sales are this month over the same time last year.',
            'Making this month not a good month to review your year-over-year results.  You are better off reviewing your actual-to-plan variance report to see where things went wrong this month.'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationOI_3_1()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::OI_3_1)->value;

        $messages = [
            'Can this level of returns and sales be repeated?',
            'Are you satisfied with these sales returns this month?',
            'It is never good to work hard for this amount of money.',
            'When last year is better than this year the question is WHY?',
            'Negative G.P. ALWAYS leads to Negative Operating Income'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationOI_3_0()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::OI_SCORE)->value;

        $messages = [
            'You generated a very healthy Operating Income this month.  Every time this happens it is a function of having above average sales, well run operations, and manageable fixed costs.',
            'The good news is you had a solid month.  Operating Income as earned this month gives you the opportunity to decide what are you going to do with your profits?',
            'You made less Operating Income this month over the same month last year because Gross Profit decreased while indirect costs increased leading to less year-over-year operating income.',
            'Your second level of profitability is compromised anytime you experience a significant decrease in sales.  When sales drop like you have fewer dollars to spread out to cover an increase in overhead costs.',
            'Extremely low G.P. always equals low O.I. unless those you pay your overhead expenses to refund you ALL the money you have to pay them.'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationNI_4_1()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::NI_4_1)->value;

        $messages = [
            'Converting higher sales into more money is always a WOW!',
            'Net Income is close to what you earned last year.',
            'Insignificant year-over-year change in Net Income.',
            'Unfortunately, last year you made more money.',
            'It is never good business to generate negative profits.  What happened?'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }

    public function interpretationNI_4_0()
    {
        $score = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::NI_SCORE)->value;

        $messages = [
            'Your success this month compared to last year is cause to pause and appreciate what key elements in your business came together to create a significant year-over-year dollar increase in Net Income.',
            'Generating an acceptable Return on Sales is impressive, particularly when on this level of sales.  This is a good month to confirm how you generated a higher return on sales this year compared to last?',
            'This month confirms that you can still earn operating profits when sales decline if you stay on top of your business like you did this month.',
            'Selling less coupled with higher costs earned you significantly less profit dollars.  The key to making more money next month is shaped by increasing sales while managing your core business expenses down.',
            'Given how poor this years results are to last year, it is better to revisit your actual-to-plan results to determine why your Net Income came in so low.'
        ];

        return $this->conditionalMessageByScore($score, $messages);
    }
}