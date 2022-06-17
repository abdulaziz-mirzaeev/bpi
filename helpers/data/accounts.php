<?php

use app\models\Account;

return [
    [
        'id' => 1,
        'name' => 'GROSS SALES',
        'visible' => Account::VISIBLE_FALSE
    ],
    [
        'id' => 2,
        'name' => 'TOTAL DISCOUNTS, + RETURNS',
        'visible' => Account::VISIBLE_FALSE
    ],
    [
        'id' => 3,
        'name' => 'TOTAL "BAD DEBT" WRITEN-OFF',
        'visible' => Account::VISIBLE_FALSE
    ],
    [
        'id' => 4,
        'name' => 'TOTAL SALES TAX, DISCOUNTS + WRITE-OFFS',
        'visible' => Account::VISIBLE_FALSE
    ],
    [
        'id' => 5,
        'name' => 'NET SALES',
        'type' => Account::TYPE_INCOME
    ],
    [
        'id' => 6,
        'name' => 'TOTAL SALES COMMISSION',
        'display_name' => 'Sales Comission',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 7,
        'name' => 'TOTAL DIRECT LABOR',
        'display_name' => 'Direct Labour',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 8,
        'name' => 'TOTAL SUBCONTRACTORS',
        'display_name' => 'Subcontractors',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 9,
        'name' => 'TOTAL MATERIALS',
        'display_name' => 'Materials & Supplies',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 10,
        'name' => 'TOTAL EQUIPMENT',
        'display_name' => 'Equipment & Repairs',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 11,
        'name' => 'TOTAL DIRECT TRANSPORTATION',
        'display_name' => 'Shipping & Transportation',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 12,
        'name' => 'TOTAL DIRECT TRAVEL',
        'display_name' => 'Direct Travel',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 13,
        'name' => 'UNIQUE COGS ITEM "B" TO BUSINESS',
        'display_name' => 'Unique DC "B"',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 14,
        'name' => 'TOTAL OTHER DIRECT COSTS',
        'display_name' => 'Other Direct Costs',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 15,
        'name' => 'COGS',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 16,
        'name' => 'GROSS PROFIT',
        'type' => Account::TYPE_INCOME
    ],
    [
        'id' => 17,
        'name' => 'TOTAL MARKETING INVESTMENT',
        'display_name' => 'Marketing',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 18,
        'name' => 'TOTAL TRAVEL & ENTERTAINMENT',
        'display_name' => 'Travel & Entertainment',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 19,
        'name' => 'TOTAL OFFICE EXPENSE',
        'display_name' => 'Office Expense',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 20,
        'name' => 'TOTAL OFFICE PAYROLL',
        'display_name' => 'Office Payroll',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 21,
        'name' => 'TOTAL INSURANCE',
        'display_name' => 'Insurance',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 22,
        'name' => 'TOTAL OUTSIDE FEES',
        'display_name' => 'Outside Fees',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 23,
        'name' => 'TOTAL PROPERTY EXPENSE',
        'display_name' => 'Property & Rent',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 24,
        'name' => 'TOTAL UTILIITIES',
        'display_name' => 'Utilities',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 25,
        'name' => 'UNIQUE SG&A ITEM "C" TO BUSINESS',
        'display_name' => 'Unique IDC "C"',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 26,
        'name' => 'UNIQUE SG&A ITEM "D" TO BUSINESS',
        'display_name' => 'Unique IDC "D"',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 27,
        'name' => 'TOTAL MISCELLANEOUS EXPENSE',
        'display_name' => 'Miscellaneous',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 28,
        'name' => 'TOTAL SG&A EXPENSE',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 29,
        'name' => 'OPERATING INCOME',
        'type' => Account::TYPE_INCOME
    ],
    [
        'id' => 30,
        'name' => 'TOTAL OTHER INCOME',
        'display_name' => 'Other Income',
        'type' => Account::TYPE_INCOME
    ],
    [
        'id' => 31,
        'name' => 'TOTAL OTHER EXPENSES',
        'display_name' => 'Other Expenses',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 32,
        'name' => 'TOTAL OWNERS COMPENSATION',
        'display_name' => 'Owners Comp & Benefits',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 33,
        'name' => 'TOTAL INTEREST',
        'display_name' => 'Interest Expense',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 34,
        'name' => 'TOTAL TAXES',
        'display_name' => 'Taxes Paid',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 35,
        'name' => 'TOTAL DEP. & AMM. EXPENSE',
        'display_name' => 'Depreciation Expense',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 36,
        'name' => 'TOTAL NONOPERATING EXPENSE LESS NONOPERATING INCOME',
        'display_name' => 'Net Nonoperating Costs',
        'type' => Account::TYPE_EXPENSE
    ],
    [
        'id' => 37,
        'name' => 'NET INCOME',
        'type' => Account::TYPE_INCOME
    ],
];