<?php


namespace app\models;


use app\enums\AccountId;
use app\enums\AccountStatement;
use app\enums\ScoreCodes;
use app\enums\RecordType;
use app\exceptions\RecordsNotFoundForDateAndTypeException;

class ReportYOY extends ReportPL
{
    public Dataset $actual;
    public Dataset $previous;
    public $date;
    public $datePrevious;

    /**
     * ReportYOY constructor.
     * @param $date
     * @throws RecordsNotFoundForDateAndTypeException
     */
    public function __construct($date)
    {
        $this->date = date('Y-m', strtotime($date));
        $this->datePrevious = date('Y-m', strtotime('-1 year', strtotime($this->date)));

        $this->actual = new Dataset($this->date, RecordType::ACTUAL);
        $this->previous = new Dataset($this->datePrevious, RecordType::ACTUAL);
    }

    public function getRecords()
    {
        return collect($this->actual->records)
            ->merge($this->previous->records)
            ->filter(function (Record $record) {
                return $record->account->visible === Account::VISIBLE_TRUE &&
                    $record->account->statement === AccountStatement::PROFIT_OR_LOSS;
            })
            ->groupBy('account_id')
            ->map(function ($recordGroup, $accountId) {
                return new RecordPairYOY(
                    $recordGroup->first(fn(Record $item) => $item->getDateF() === $this->date),
                    $recordGroup->first(fn(Record $item) => $item->getDateF() === $this->datePrevious),
                    $accountId,
                    $this,
                );
            })
            ->all();
    }

    public function getReturnOnSalesAndGPInterpretation()
    {
        $scoreA = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::GP_SCORE)->value;
        $scoreB = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::GP_ROS_PERCENT_CHANGE_SCORE)->value;

        $score = $scoreA + $scoreB;


        $intervals = [
            [15, 20],
            [7, 14],
            [-6, 6],
            [-14, -7],
            [-20, -15],
        ];

        $messages = [
            "Do you feel you can sustain this level of Gross Margin?",
            "Lower sales will always make expense management tougher",
            "Are you satisfied with this months Return on Sales?",
            "Cash flow issues always follow decreases in Gross Profit",
            "A poor Return on Sales always leads to immediate business problems",
        ];

        return $this->conditionalMessageByScore($score, $messages, $intervals);
    }

    public function getNetIncomeAndROSInterpretation()
    {
        $scoreA = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::NI_SCORE)->value;
        $scoreB = YearOverYearScore::getByDateAndCode($this->date, ScoreCodes::NI_ROS_PERCENT_CHANGE_SCORE)->value;

        $score = $scoreA + $scoreB;

        $intervals = [
            [15, 20],
            [7, 14],
            [-6, 6],
            [-14, -7],
            [-20, -15],
        ];

        $messages = [
            "This month confirms the impact operations has on your business profitability.  The money your business ultimately makes in Net Income is driven by how much Gross Profit you generate from Net Sales.  Earning  10% in Gross Profit Margin is impressive The question is was this by accident of by management control?",
            "Your year-over-year sales improvement is making this a better  year.  The question to answer is do you consider this an acceptable return on the effort you are expending and the risk you were exposed to?  If yes, then stop your year-over-year review here.",
            "You made less money this year over last    despite a   year-over-year increase in your COGS.   During this scoreboard review step back and reflect on how you earned a Gross Profit of  $306,312  last year compared to  $161,918  this year.",
            "The red shaded cells to the right tell you where you spent more as a percent of sales this year over last.   Do you feel this was a function of reduced expense productivity because of lower sales volume worked?",
            "The sea of red shaded cells to the right represents spending more as a percent of sales this year over last.  This is a common problem when costs are not reduced proportionate to sales.  Given the significant drop-off in sales you are better off studying the actual-to-plan scoreboard to see where you need to do better.",
        ];

        return $this->conditionalMessageByScore($score, $messages, $intervals);
    }
}