<?php

return [

    'app_id' => env('ZALOPAY_APP_ID'),

    'key1' => env('ZALOPAY_KEY1'),

    'key2' => env('ZALOPAY_KEY2'),

    'endpoint' => env(
        'ZALOPAY_ENDPOINT',
        'https://sb-openapi.zalopay.vn/v2/create'
    ),

    'callback_url' => env('ZALOPAY_CALLBACK'),

    'redirect_url' => env('ZALOPAY_REDIRECT'),

    'bank_list_endpoint' => env(
        'ZALOPAY_BANK_LIST_ENDPOINT',
        'https://sbgateway.zalopay.vn/api/getlistmerchantbanks'
    )

];