<?php


namespace app\models;


use Tightenco\Collect\Support\Collection;

class YearOverYearScoreCriteria extends ActualToPlanScoreCriteria
{
    protected static array $data = [
        ['id' => 1,'code' => '1.0', 'name' => 'Net Sales Score', 'description' => "(I.e., Is current sales too low?)"],
        ['id' => 2,'code' => '1.1', 'name' => 'N.S. $ Change Score', 'description' => "(I.e., How much has sales changed?)"],
        ['id' => 3,'code' => '1.3', 'name' => 'N.S. % Change Score', 'description' => "(I.e., How much did Sales grow?)"],
        ['id' => 4,'code' => '2.0', 'name' => 'G.P. Score', 'description' => "(I.e., Is current Gross Profit too low?)"],
        ['id' => 5,'code' => '2.1', 'name' => 'G.P. $ Change Score', 'description' => "(I.e., How much has Gross Profit $'s changed?)"],
        ['id' => 6,'code' => '2.2', 'name' => 'G.P. % of Sales Score', 'description' => "(I.e., Is current G.P. % of Sales too low?)"],
        ['id' => 7,'code' => '2.3', 'name' => 'G.P. % Change Score', 'description' => "(I.e., How much did G.P. grow?)"],
        ['id' => 8,'code' => '2.4', 'name' => 'G.P. ROS % Change Score', 'description' => "(I.e., How much did ROS change?)"],
        ['id' => 9,'code' => '3.0', 'name' => 'O.I. Score', 'description' => "(I.e., Is current Operating Income too low?)"],
        ['id' => 10,'code' => '3.1', 'name' => 'O.I. $ Change Score', 'description' => "(I.e., How much has Operating Income $'s changed?)"],
        ['id' => 11,'code' => '3.2', 'name' => 'O.I. % of Sales Score', 'description' => "(I.e., Is current Operating Income % of Sales too low?)"],
        ['id' => 12,'code' => '3.3', 'name' => 'O.I. % Change Score', 'description' => "(I.e., How much did O.I. grow?)"],
        ['id' => 13,'code' => '3.4', 'name' => 'O.I. ROS % Change Score', 'description' => "(I.e., How much did ROS change?)"],
        ['id' => 14,'code' => '4.0', 'name' => 'N.I. Score', 'description' => "(I.e., Is current Net Income too low?)"],
        ['id' => 15,'code' => '4.1', 'name' => 'N.I. $ Change Score', 'description' => "(I.e., How much has N.I. $'s changed?)"],
        ['id' => 16,'code' => '4.2', 'name' => 'N.I. % of Sales Score', 'description' => "(I.e., Is current N.I. % of Sales too low?)"],
        ['id' => 17,'code' => '4.3', 'name' => 'N.I. % Change Score', 'description' => "(I.e., How much did N.I. grow?)"],
        ['id' => 18,'code' => '4.4', 'name' => 'N.I. ROS % Change Score', 'description' => "(I.e., How much did ROS change?)"],

    ];

}