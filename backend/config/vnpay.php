<?php

return [
    'tmn_code' => env('VNP_TMN_CODE', 'QDSVNT93'),
    'hash_secret' => env('VNP_HASH_SECRET', 'W1T3AMFXLIG0MYO1SRA4Q2R6C1W484UU'),
    'url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url' => env('VNP_RETURN_URL', 'http://localhost:3000/payment-result'),
];
