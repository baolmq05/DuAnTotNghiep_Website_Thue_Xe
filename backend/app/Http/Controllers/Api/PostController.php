<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Enum\PostStatus;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * API Lấy danh sách bài viết
     * GET /api/posts
     */
    public function index(Request $request)
    {
        $query = Post::query();

        // Chỉ lấy các bài viết có trạng thái Active (1)
        $query->where('status', PostStatus::Active);

        // Lọc theo category
        if ($request->filled('category_id')) {
            $query->where('post_category_id', $request->category_id);
        }

        // Tìm kiếm theo tiêu đề, mô tả ngắn, hoặc nội dung
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Eager load category và user (author)
        $query->with(['category', 'user' => function ($q) {
            $q->select('id', 'name', 'avatar');
        }]);

        // Sắp xếp bài viết mới nhất lên đầu
        $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');

        // Phân trang
        $limit = $request->input('limit', 6);
        $posts = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách bài viết thành công',
            'data' => $posts
        ]);
    }

    /**
     * API Xem chi tiết bài viết
     * GET /api/posts/{id}
     */
    public function show($id)
    {
        $post = Post::with(['category', 'user' => function ($q) {
            $q->select('id', 'name', 'avatar');
        }])->find($id);

        if (!$post || $post->status !== PostStatus::Active) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết hoặc bài viết chưa được công khai'
            ], 404);
        }

        // Lấy 3 bài viết liên quan (cùng danh mục, loại trừ bài viết hiện tại, sắp xếp mới nhất)
        $relatedPosts = Post::where('status', PostStatus::Active)
            ->where('post_category_id', $post->post_category_id)
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['id', 'title', 'excerpt', 'thumbnail', 'published_at']);

        $post->setAttribute('related_posts', $relatedPosts);

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết bài viết thành công',
            'data' => $post
        ]);
    }

    /**
     * API Lấy danh sách danh mục hoạt động
     * GET /api/post-categories
     */
    public function categories()
    {
        // status = 1 là hoạt động cho post_categories
        $categories = PostCategory::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách danh mục thành công',
            'data' => $categories
        ]);
    }
}
