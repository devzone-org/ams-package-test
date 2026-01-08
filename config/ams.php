<?php

return [
    'prefix' => 'accounts',
    'middleware' => ['web','auth'],
    'ledger_settlement_enabled' => env('AMS_LEDGER_SETTLEMENT_ENABLED', false),
    'customer_model' => \Devzone\Ams\Models\AmsCustomer::class,
    'customer_table_map' => [
        'name' => 'name',
        'code' => 'customer_code',
        'account_id' => 'account_id',
        'status_column' => 'status',
        'active_status_value' => 'Active',
        'credit_limit' => 'amount',
        'payment_terms' => 'frequency',
        'grace_period' => 'grace_period',
    ],
];
