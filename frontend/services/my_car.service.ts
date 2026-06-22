import { API_URL } from "~/enviroment/enviroment";

export interface Car {
  id: number;
  name: string;
  license_plate: string;
  fuel_consumption: number;
  unit_price: number;
  discount_value: number;
  description: string;
  rental_terms: string;
  car_location_id: number;
  car_brand_id: number;
  car_type_id: number;
  seat_count: number;
  manufacture_year: string;
  fuel_type: string;
  transmission: string;
  user_id: number;
  delivery_option_id: number;
  usage_limit_id: number;
  status: number;
  car_location?: {
    id: number;
    location: string;
    address: string;
  };
  car_brand?: {
    id: number;
    name: string;
  };
  car_type?: {
    id: number;
    name: string;
  };
  images?: Array<{
    id: number;
    car_id: number;
    image_url: string;
    is_thumbnail: number;
  }>;
  reviews_avg_rating?: string | number | null;
  trips_count?: number;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

export class MyCarService {
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
   * Lấy danh sách xe kèm các bộ lọc (user_id, status, v.v...)
   */
  async getCars(params: {
    user_id?: number | string;
    status?: number | string;
    brand_id?: number | string;
    type_id?: number | string;
  } = {}): Promise<ApiResponse<Car[]>> {
    const queryParams = new URLSearchParams();
    if (params.user_id) queryParams.append("user_id", params.user_id.toString());
    if (params.status !== undefined && params.status !== "all") {
      queryParams.append("status", params.status.toString());
    }
    if (params.brand_id) queryParams.append("brand_id", params.brand_id.toString());
    if (params.type_id) queryParams.append("type_id", params.type_id.toString());

    const queryString = queryParams.toString();
    const url = queryString ? `cars?${queryString}` : "cars";

    return this.request<ApiResponse<Car[]>>(url, "GET");
  }
}

export const myCarService = new MyCarService();
