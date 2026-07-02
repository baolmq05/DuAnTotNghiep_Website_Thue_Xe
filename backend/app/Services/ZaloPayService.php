<?php

namespace App\Services;

use App\Enum\TripStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Trip;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ZaloPayService
{
    protected $appId;
    protected $key1;
    protected $key2;
    protected $endpoint;
    protected $callbackUrl;
    protected $redirectUrl;
    protected $bankListEndpoint;

    public function __construct()
    {
        $this->appId = config('zalopay.app_id');
        $this->key1 = config('zalopay.key1');
        $this->key2 = config('zalopay.key2');

        $this->endpoint = config('zalopay.endpoint');

        $this->callbackUrl = config('zalopay.callback_url');

        $this->redirectUrl = config('zalopay.redirect_url');

        $this->bankListEndpoint = config('zalopay.bank_list_endpoint');
    }

    /**
     * Tạo URL thanh toán ZaloPay
     *
     * @param string $appTransId
     * @param float $amount
     * @param string $description
     *
     * @return array
     */
    public function createPaymentUrl(
        string $appTransId,
        float $amount,
        int $userId,
        string $description
    ): array {

        $embedData = [

            "redirecturl" => $this->redirectUrl,

            "merchantinfo" => "Trip Payment",

            "promotioninfo" => ""

        ];

        $items = [

            [

                "itemid" => $appTransId,

                "itemname" => $description,

                "itemprice" => $amount,

                "itemquantity" => 1

            ]

        ];

        $appTime = round(microtime(true) * 1000);

        $order = [
            "app_id" => $this->appId,

            "app_user" => $userId,

            "app_trans_id" => $appTransId,

            "app_time" => $appTime,

            "amount" => (int) $amount,

            "item" => json_encode(
                $items,
                JSON_UNESCAPED_UNICODE
            ),

            "embed_data" => json_encode(
                $embedData,
                JSON_UNESCAPED_UNICODE
            ),

            "description" => $description,

            "callback_url" => $this->callbackUrl
        ];

        /**
         * Chuỗi tạo MAC
         *
         * app_id
         * |
         * app_trans_id
         * |
         * app_user
         * |
         * amount
         * |
         * app_time
         * |
         * embed_data
         * |
         * item
         */

        $data =
            $order["app_id"] . "|" .
            $order["app_trans_id"] . "|" .
            $order["app_user"] . "|" .
            $order["amount"] . "|" .
            $order["app_time"] . "|" .
            $order["embed_data"] . "|" .
            $order["item"];

        $order["mac"] = hash_hmac(
            "sha256",
            $data,
            $this->key1
        );

        Log::info("ZaloPay Create Order", $order);

        $response = Http::asForm()->post(
            $this->endpoint,
            $order
        );

        if (!$response->successful()) {

            Log::error(
                "ZaloPay HTTP Error",
                [
                    "status" => $response->status(),
                    "body" => $response->body()
                ]
            );

            throw new \Exception("Không thể kết nối tới ZaloPay.");
        }

        $result = $response->json();

        Log::info("ZaloPay Response", $result);

        if (($result["return_code"] ?? -1) != 1) {

            throw new \Exception(
                $result["return_message"]
                ?? "Tạo đơn thanh toán thất bại."
            );
        }

        return [
            "success" => true,

            "app_trans_id" => $appTransId,

            "zp_trans_token" => $result["zp_trans_token"] ?? null,

            "order_url" => $result["order_url"]
                ?? $result["orderurl"]
                ?? null,

            "data" => $result
        ];
    }
    /**
     * Xác thực callback từ ZaloPay
     */
    public function verifyCallback(Request $request): array
    {
        $data = $request->input('data');

        $mac = $request->input('mac');

        if (!$data || !$mac) {

            return [

                "return_code" => -1,

                "return_message" => "Missing data"

            ];

        }

        $verifyMac = hash_hmac(

            "sha256",

            $data,

            $this->key2

        );

        if (!hash_equals($verifyMac, $mac)) {

            return [

                "return_code" => -1,

                "return_message" => "Invalid MAC"

            ];

        }

        $json = json_decode($data, true);

        if (!$json) {

            return [

                "return_code" => -1,

                "return_message" => "Invalid JSON"

            ];

        }

        $res = $this->processPayment(
            $json["app_trans_id"],
            $json["amount"],
            $json["zp_trans_id"],
            $this->parseAppTransId(
                $json["app_trans_id"]
            )["type"]
        );

        return ($res["success"] ?? false)
            ? [
                "return_code" => 1,
                "return_message" => "success"
            ]
            : [
                "return_code" => 0,
                "return_message" => "fail"
            ];
    }
    /**
     * Kiểm tra trạng thái giao dịch
     */
    public function queryTransaction(string $appTransId): array
    {
        $data = [

            "app_id" => $this->appId,

            "app_trans_id" => $appTransId

        ];

        $dataString =
            $data["app_id"] .
            "|" .
            $data["app_trans_id"] .
            "|" .
            $this->key1;

        $data["mac"] = hash_hmac(
            "sha256",
            $dataString,
            $this->key1
        );

        $data["mac"] = hash_hmac(
            "sha256",
            $dataString,
            $this->key1
        );

        $response = Http::asForm()->post(

            "https://sb-openapi.zalopay.vn/v2/query",

            $data

        );

        if (!$response->successful()) {

            throw new \Exception(
                "Không thể truy vấn giao dịch."
            );
        }

        return $response->json();
    }
    /**
     * rental_15_8_17532525
     * deposit_5_17532525
     * penalty_8_5_17532525
     */

    public function parseAppTransId(
        string $appTransId
    ): array {
        $parts = explode("_", $appTransId);

        $type = $parts[1] ?? "unknown";

        switch ($type) {

            case "rental":

                return [

                    "type" => "rental",

                    "trip_id" => intval($parts[2]),

                    "owner_id" => intval($parts[3])

                ];

            case "deposit":

                return [

                    "type" => "deposit",

                    "user_id" => intval($parts[2])

                ];

            case "penalty":

                return [

                    "type" => "penalty",

                    "trip_id" => intval($parts[2]),

                    "user_id" => intval($parts[3])

                ];

            default:

                return [

                    "type" => "unknown"

                ];
        }

    }

    /**
     * Xử lý giao dịch sau khi ZaloPay callback
     */
    public function processPayment(
        string $appTransId,
        float $amount,
        string $zpTransactionId,
        string $paymentType
    ): array {

        $meta = $this->parseAppTransId($appTransId);

        return DB::transaction(function () use ($meta, $amount, $paymentType, $zpTransactionId) {

            /**
             * Tránh callback nhiều lần
             */

            $exists = Transaction::where(
                "transaction_code",
                $zpTransactionId
            )->exists();

            if ($exists) {

                return [

                    "success" => false,

                    "message" => "Giao dịch đã xử lý."

                ];

            }

            switch ($paymentType) {

                /**
                 * ==========================
                 * Thanh toán thuê xe
                 * ==========================
                 */

                case "rental":

                    $trip = Trip::find(
                        $meta["trip_id"]
                    );

                    if (!$trip) {

                        return [

                            "success" => false,

                            "message" => "Không tìm thấy chuyến đi."

                        ];

                    }

                    $owner = User::find(
                        $meta["owner_id"]
                    );

                    if ($owner) {

                        if (!$owner->wallet_id) {

                            $wallet = Wallet::create([

                                "amount" => 0

                            ]);

                            $owner->wallet_id = $wallet->id;

                            $owner->save();

                        } else {

                            $wallet = $owner->wallet;

                        }

                        $wallet->increment(

                            "amount",

                            $amount

                        );

                    }

                    Transaction::create([

                        "user_id" => $trip->user_id,

                        "transaction_code" => $zpTransactionId,

                        "amount" => $amount,

                        "prepay" => $amount,

                        "trip_id" => $trip->id

                    ]);

                    $trip->status = TripStatus::Confirmed->value;

                    $trip->save();

                    break;

                /**
                 * ==========================
                 * Nạp ví
                 * ==========================
                 */

                case "deposit":

                    $user = User::find(

                        $meta["user_id"]

                    );

                    if (!$user) {

                        return [

                            "success" => false,

                            "message" => "Không tìm thấy người dùng."

                        ];

                    }

                    if (!$user->wallet_id) {

                        $wallet = Wallet::create([

                            "amount" => 0

                        ]);

                        $user->wallet_id = $wallet->id;

                        $user->save();

                    } else {

                        $wallet = $user->wallet;

                    }

                    $wallet->increment(

                        "amount",

                        $amount

                    );

                    Transaction::create([

                        "user_id" => $user->id,

                        "transaction_code" => $zpTransactionId,

                        "amount" => $amount,

                        "prepay" => 0,

                        "trip_id" => null

                    ]);

                    break;

                /**
                 * ==========================
                 * Thanh toán tiền phạt
                 * ==========================
                 */

                case "penalty":

                    $user = User::find(

                        $meta["user_id"]

                    );

                    if (!$user) {

                        return [

                            "success" => false,

                            "message" => "Không tìm thấy người dùng."

                        ];

                    }

                    Transaction::create([

                        "user_id" => $user->id,

                        "transaction_code" => $zpTransactionId,

                        "amount" => $amount,

                        "prepay" => 0,

                        "trip_id" => $meta["trip_id"]

                    ]);

                    break;

                default:

                    return [

                        "success" => false,

                        "message" => "Payment type không hợp lệ."

                    ];

            }

            return [

                "success" => true,

                "message" => "Thanh toán thành công."

            ];

        });

    }

    /**
     * Lấy danh sách các ngân hàng được ZaloPay hỗ trợ
     *
     * @return array
     */
    public function getBanks(): array
    {
        $reqTime = round(microtime(true) * 1000);
        $dataString = $this->appId . "|" . $reqTime;
        $mac = hash_hmac("sha256", $dataString, $this->key1);

        $params = [
            'appid' => $this->appId,
            'reqtime' => $reqTime,
            'mac' => $mac
        ];

        $response = \Illuminate\Support\Facades\Http::asForm()->post($this->bankListEndpoint, $params);

        if (!$response->successful()) {
            \Illuminate\Support\Facades\Log::error("ZaloPay GetBanks HTTP Error", [
                "status" => $response->status(),
                "body" => $response->body()
            ]);
            throw new \Exception("Không thể kết nối tới ZaloPay để lấy danh sách ngân hàng.");
        }

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info("ZaloPay GetBanks Response", $result);

        return $result;
    }
}