import { BaseService } from "./base.service";

export class AuthService extends BaseService {
  constructor() {
    super("auth");
  }

  async loginApi(payload: any): Promise<any> {
    return this.request<any>("auth/login", {
      method: "POST",
      body: payload,
      useAuth: false
    });
  }

  async registerApi(payload: any): Promise<any> {
    return this.request<any>("auth/register", {
      method: "POST",
      body: payload,
      useAuth: false
    });
  }

  async logoutApi(): Promise<any> {
    return this.request<any>("auth/logout", {
      method: "POST",
      useAuth: true
    });
  }

  async getProfileApi(): Promise<any> {
    return this.request<any>("auth/profile", {
      method: "GET",
      useAuth: true
    });
  }

  async updateProfileApi(payload: any): Promise<any> {
    return this.request<any>("auth/profile", {
      method: "PUT",
      body: payload,
      useAuth: true
    });
  }

  async changePasswordApi(payload: any): Promise<any> {
    return this.request<any>("auth/change-password", {
      method: "POST",
      body: payload,
      useAuth: true
    });
  }

  async loginWithGoogleApi(token: string): Promise<any> {
    return this.request<any>("auth/google", {
      method: "POST",
      body: { token },
      useAuth: false
    });
  }

  async loginWithFacebookApi(accessToken: string): Promise<any> {
    return this.request<any>("auth/facebook", {
      method: "POST",
      body: { accessToken },
      useAuth: false
    });
  }
}

export const authService = new AuthService();
