<?php

return [
    'tmn_code' => env('VNP_TMN_CODE', '3GXMZSMG'),
    'hash_secret' => env('VNP_HASH_SECRET', '8SP1QX8KH7CJSG96VOIGUP1H3D9NAFAC'),
    'url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url' => env('VNP_RETURN_URL', 'http://localhost:3000/payment-result'),
];
