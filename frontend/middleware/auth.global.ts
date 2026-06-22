export default defineNuxtRouteMiddleware((to, from) => {
  const token = useCookie("USER_TOKEN");
  const user = useCookie("USER_INFO");

  const protectedPaths = [
    "/chats",
    "/notifications",
    "/profile",
    "/my-cars",
    "/mywallet",
    "/mymonthlyreport"
  ];

  const requiresAuth = protectedPaths.some(
    (path) => to.path === path || to.path.startsWith(path + "/")
  );

  if (requiresAuth && (!token.value || !user.value)) {
    return navigateTo("/?showLogin=true");
  }
});
