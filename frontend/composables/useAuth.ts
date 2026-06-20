import { useCookie } from "#app";
import { computed } from "vue";
import { authService } from "~/services/auth.service";

export const useAuth = () => {
  const token = useCookie<string | null>("USER_TOKEN", { maxAge: 60 * 60 * 24 * 7, path: '/' });
  const user = useCookie<any>("USER_INFO", { maxAge: 60 * 60 * 24 * 7, path: '/' });

  const login = async (credentials: { email: string; password: string }) => {
    try {
      const res: any = await authService.loginApi(credentials);
      if (res && res.access_token) {
        token.value = res.access_token;
        user.value = res.user || null;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_TOKEN", res.access_token);
          if (res.user) {
            localStorage.setItem("USER_INFO", JSON.stringify(res.user));
          }
        }
        return { success: true };
      }
      return { success: false, message: "Không nhận được access token." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || err.response?._data?.error || "Đăng nhập thất bại.";
      return { success: false, message: errMsg, errors: err.response?._data?.errors };
    }
  };

  const register = async (userData: any) => {
    try {
      const res: any = await authService.registerApi(userData);
      if (res && res.success) {
        const accessToken = res.token_info.access_token;
        token.value = accessToken;
        user.value = res.user;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_TOKEN", accessToken);
          localStorage.setItem("USER_INFO", JSON.stringify(res.user));
        }
        return { success: true };
      }
      return { success: false, message: "Đăng ký thất bại." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || "Đăng ký thất bại.";
      return { success: false, message: errMsg, errors: err.response?._data?.errors };
    }
  };

  const updateProfile = async (profileData: any) => {
    try {
      const res: any = await authService.updateProfileApi(profileData);
      if (res && res.success) {
        user.value = res.user;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_INFO", JSON.stringify(res.user));
        }
        return { success: true, message: res.message };
      }
      return { success: false, message: "Cập nhật hồ sơ thất bại." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || "Cập nhật hồ sơ thất bại.";
      return { success: false, message: errMsg, errors: err.response?._data?.errors };
    }
  };

  const submitDrivingLicense = async (formData: FormData) => {
    try {
      const res: any = await authService.submitDrivingLicenseApi(formData);
      if (res && res.success) {
        user.value = res.user;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_INFO", JSON.stringify(res.user));
        }
        return { success: true, message: res.message };
      }
      return { success: false, message: "Gửi duyệt bằng lái thất bại." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || "Gửi duyệt bằng lái thất bại.";
      return { success: false, message: errMsg, errors: err.response?._data?.errors };
    }
  };

  const changePassword = async (passwordData: any) => {
    try {
      const res: any = await authService.changePasswordApi(passwordData);
      if (res && res.success) {
        return { success: true, message: res.message };
      }
      return { success: false, message: "Đổi mật khẩu thất bại." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || "Đổi mật khẩu thất bại.";
      return { success: false, message: errMsg, errors: err.response?._data?.errors };
    }
  };

  const logout = async () => {
    try {
      if (token.value) {
        await authService.logoutApi();
      }
    } catch (e) {
      console.error("Error calling logout API", e);
    } finally {
      token.value = null;
      user.value = null;
      if (typeof window !== "undefined") {
        localStorage.removeItem("USER_TOKEN");
        localStorage.removeItem("USER_INFO");
      }
    }
  };

  const loginWithGoogle = async (googleToken: string) => {
    try {
      const res: any = await authService.loginWithGoogleApi(googleToken);
      if (res && res.access_token) {
        token.value = res.access_token;
        user.value = res.user || null;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_TOKEN", res.access_token);
          if (res.user) {
            localStorage.setItem("USER_INFO", JSON.stringify(res.user));
          }
        }
        return { success: true, user: res.user };
      }
      return { success: false, message: "Không nhận được access token từ Google." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || err.response?._data?.error || "Đăng nhập Google thất bại.";
      return { success: false, message: errMsg };
    }
  };

  const loginWithFacebook = async (fbAccessToken: string) => {
    try {
      const res: any = await authService.loginWithFacebookApi(fbAccessToken);
      if (res && res.access_token) {
        token.value = res.access_token;
        user.value = res.user || null;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_TOKEN", res.access_token);
          if (res.user) {
            localStorage.setItem("USER_INFO", JSON.stringify(res.user));
          }
        }
        return { success: true, user: res.user };
      }
      return { success: false, message: "Không nhận được access token từ Facebook." };
    } catch (err: any) {
      console.error(err);
      const errMsg = err.response?._data?.message || err.response?._data?.error || "Đăng nhập Facebook thất bại.";
      return { success: false, message: errMsg };
    }
  };

  const isLoggedIn = computed(() => !!token.value && !!user.value);

  return {
    user,
    token,
    login,
    register,
    updateProfile,
    submitDrivingLicense,
    changePassword,
    logout,
    loginWithGoogle,
    loginWithFacebook,
    isLoggedIn
  };
};
