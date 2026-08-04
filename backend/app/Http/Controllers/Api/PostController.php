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
     * Get list of posts with search and category filters.
     * GET /api/posts
     */
    public function index(Request $request)
    {
        $query = Post::query();

        // Only retrieve active posts
        $query->where('status', PostStatus::Active);

        // Filter by category if category_id is provided
        if ($request->filled('category_id')) {
            $query->where('post_category_id', $request->category_id);
        }

        // Search by title, excerpt, or content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Eager load category and user (author) relations
        $query->with(['category', 'user' => function ($q) {
            $q->select('id', 'name', 'avatar');
        }]);

        // Order posts by published_at and created_at descending (latest first)
        $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');

        // Paginate results
        $limit = $request->input('limit', 6);
        $posts = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách bài viết thành công',
            'data' => $posts
        ]);
    }

    /**
     * Get details of a single post by slug.
     * GET /api/posts/{slug}
     */
    public function show(string $slug)
    {
        // Find active post by slug with relations
        $post = Post::with(['category', 'user' => function ($q) {
            $q->select('id', 'name', 'avatar');
        }])
        ->where('slug', $slug)
        ->where('status', PostStatus::Active)
        ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết hoặc bài viết chưa được công khai'
            ], 404);
        }

        // Retrieve 3 related posts from the same category (excluding current post)
        $relatedPosts = Post::where('status', PostStatus::Active)
            ->where('post_category_id', $post->post_category_id)
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['id', 'slug', 'title', 'excerpt', 'thumbnail', 'published_at']);

        // Attach related posts to the post object
        $post->setAttribute('related_posts', $relatedPosts);

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết bài viết thành công',
            'data' => $post
        ]);
    }

    /**
     * Get list of active post categories.
     * GET /api/post-categories
     */
    public function categories()
    {
        // status = 1 indicates active categories
        $categories = PostCategory::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách danh mục thành công',
            'data' => $categories
        ]);
    }
}
