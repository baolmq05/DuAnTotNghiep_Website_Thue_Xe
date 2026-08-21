<?php

namespace App\Http\Controllers\Api;

use App\Actions\OwnerReport\GetOwnerReportDetailAction;
use App\Actions\OwnerReport\GetOwnerReportsAction;
use App\Actions\OwnerReport\GetOwnerReportSummaryAction;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OwnerReportController extends Controller
{
    /**
     * Get summary statistics of reports and strikes for the authenticated car owner.
     * GET /api/owner/reports/summary
     */
    public function summary(GetOwnerReportSummaryAction $action): JsonResponse
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.',
            ], 401);
        }

        $summaryData = $action->execute($user);

        return response()->json([
            'success' => true,
            'data' => $summaryData,
        ], 200);
    }

    /**
     * Get paginated and filtered list of reports for the authenticated car owner.
     * GET /api/owner/reports
     */
    public function index(Request $request, GetOwnerReportsAction $action): JsonResponse
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.',
            ], 401);
        }

        $result = $action->execute($user, $request->all());

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'pagination' => $result['pagination'],
        ], 200);
    }

    /**
     * Get details of a single report for the authenticated car owner.
     * GET /api/owner/reports/{id}
     */
    public function show(int|string $id, GetOwnerReportDetailAction $action): JsonResponse
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.',
            ], 401);
        }

        try {
            $data = $action->execute($user, $id);

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (AccessDeniedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }
}
