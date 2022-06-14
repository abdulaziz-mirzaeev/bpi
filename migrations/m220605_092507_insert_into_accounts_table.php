<?php

use app\models\Account;
use yii\db\Migration;

/**
 * Class m220605_092507_insert_into_accounts_table
 */
class m220605_092507_insert_into_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%accounts}}', ['name' => 'GROSS SALES', 'visible' => Account::VISIBLE_FALSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL DISCOUNTS, + RETURNS','visible' => Account::VISIBLE_FALSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL "BAD DEBT" WRITEN-OFF', 'visible' => Account::VISIBLE_FALSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL SALES TAX, DISCOUNTS + WRITE-OFFS', 'visible' => Account::VISIBLE_FALSE]);
        $this->insert('{{%accounts}}', ['name' => 'NET SALES', 'type' => Account::TYPE_INCOME]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL SALES COMMISSION', 'display_name' => 'Sales Comission', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL DIRECT LABOR', 'display_name' => 'Direct Labour', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL SUBCONTRACTORS', 'display_name' => 'Subcontractors', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL MATERIALS', 'display_name' => 'Materials & Supplies', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL EQUIPMENT', 'display_name' => 'Equipment & Repairs', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL DIRECT TRANSPORTATION', 'display_name' => 'Shipping & Transportation', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL DIRECT TRAVEL', 'display_name' => 'Direct Travel', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'UNIQUE COGS ITEM "B" TO BUSINESS', 'display_name' => 'Unique DC "B"', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OTHER DIRECT COSTS', 'display_name' => 'Other Direct Costs', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'COGS', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'GROSS PROFIT', 'type' => Account::TYPE_INCOME]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL MARKETING INVESTMENT', 'display_name' => 'Marketing', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL TRAVEL & ENTERTAINMENT', 'display_name' => 'Travel & Entertainment', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OFFICE EXPENSE', 'display_name' => 'Office Expense', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OFFICE PAYROLL', 'display_name' => 'Office Payroll', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL INSURANCE', 'display_name' => 'Insurance', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OUTSIDE FEES', 'display_name' => 'Outside Fees', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL PROPERTY EXPENSE', 'display_name' => 'Property & Rent', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL UTILIITIES', 'display_name' => 'Utilities', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'UNIQUE SG&A ITEM "C" TO BUSINESS', 'display_name' => 'Unique IDC "C"', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'UNIQUE SG&A ITEM "D" TO BUSINESS', 'display_name' => 'Unique IDC "D"', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL MISCELLANEOUS EXPENSE', 'display_name' => 'Miscellaneous', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL SG&A EXPENSE', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'OPERATING INCOME', 'type' => Account::TYPE_INCOME]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OTHER INCOME', 'display_name' => 'Other Income', 'type' => Account::TYPE_INCOME]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OTHER EXPENSES', 'display_name' => 'Other Expenses', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL OWNERS COMPENSATION', 'display_name' => 'Owners Comp & Benefits', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL INTEREST', 'display_name' => 'Interest Expense', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL TAXES', 'display_name' => 'Taxes Paid', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL DEP. & AMM. EXPENSE', 'display_name' => 'Depreciation Expense', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'TOTAL NONOPERATING EXPENSE LESS NONOPERATING INCOME', 'display_name' => 'Net Nonoperating Costs', 'type' => Account::TYPE_EXPENSE]);
        $this->insert('{{%accounts}}', ['name' => 'NET INCOME', 'type' => Account::TYPE_INCOME]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%accounts}}');
    }

}
