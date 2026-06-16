import { API_URL } from "~/enviroment/enviroment";

export interface Post {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  thumbnail: string | null;
  user_id: number;
  post_category_id: number;
  status: number;
  type: string;
  published_at: string | null;
  created_at: string;
  updated_at: string;
  category?: {
    id: number;
    name: string;
    status: number;
  };
  user?: {
    id: number;
    name: string;
    avatar: string | null;
  };
  related_posts?: Array<{
    id: number;
    title: string;
    excerpt: string;
    thumbnail: string | null;
    published_at: string | null;
  }>;
}

export interface PostCategory {
  id: number;
  name: string;
  status: number;
  created_at: string;
  updated_at: string;
}

export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: Array<{ url: string | null; label: string; active: boolean }>;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

export class PostService {
  private async request<T>(
    url: string,
    method: "GET" | "POST" = "GET",
    body?: any
  ): Promise<T> {
    try {
      return await $fetch<T>(`${API_URL}${url}`, {
        method,
        body,
        headers: {
          "Content-Type": "application/json"
        }
      });
    } catch (err) {
      console.error(`[API ERROR] ${url}`, err);
      throw err;
    }
  }

  /**
   * Lấy danh sách bài viết (có phân trang, lọc theo danh mục, tìm kiếm)
   */
  async getPostsApi(params: {
    page?: number;
    category_id?: number | string;
    search?: string;
    limit?: number;
  }): Promise<ApiResponse<PaginatedResponse<Post>>> {
    const queryParams = new URLSearchParams();
    if (params.page) queryParams.append("page", params.page.toString());
    if (params.category_id) queryParams.append("category_id", params.category_id.toString());
    if (params.search) queryParams.append("search", params.search);
    if (params.limit) queryParams.append("limit", params.limit.toString());

    const queryString = queryParams.toString();
    const url = queryString ? `posts?${queryString}` : "posts";

    return this.request<ApiResponse<PaginatedResponse<Post>>>(url, "GET");
  }

  /**
   * Lấy chi tiết bài viết
   */
  async getPostDetailApi(id: string | number): Promise<ApiResponse<Post>> {
    return this.request<ApiResponse<Post>>(`posts/${id}`, "GET");
  }

  /**
   * Lấy danh sách danh mục bài viết
   */
  async getCategoriesApi(): Promise<ApiResponse<PostCategory[]>> {
    return this.request<ApiResponse<PostCategory[]>>("post-categories", "GET");
  }
}

export const postService = new PostService();
