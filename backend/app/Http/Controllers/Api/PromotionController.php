<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json([
            'success' => true,
            'message' => 'Danh sách khuyến mãi.',
            'data' => $promotions,
        ]);
    }
    public function show($id)
    {
        $promotion = Promotion::find($id);
        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Khuyến mãi không tồn tại.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Chi tiết khuyến mãi.',
            'data' => $promotion,
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|unique:promotions,code',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'discount_type' => 'required|in:0,1',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            $promotion = Promotion::create($validatedData);
    
            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
    
            $promotion = Promotion::findOrFail($id);
    
            $validatedData = $request->validate([
                'code' => 'required|unique:promotions,code,' . $id,
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'discount_type' => 'required|in:0,1',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'usage_limit' => 'required|integer',
                'per_user_limit' => 'required|integer',
                'status' => 'required|in:0,1',
                'user_id' => 'nullable',
            ]);
    
            $promotion->update($validatedData);
    
            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa khuyến mãi thành công.',
        ]);
    }
}

