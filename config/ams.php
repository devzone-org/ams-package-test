<?php

return [
    'prefix' => 'accounts',
    'middleware' => ['web','auth'],
    'ledger_settlement_enabled' => env('AMS_LEDGER_SETTLEMENT_ENABLED', false),
];
