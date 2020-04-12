import bearer from "@websanova/vue-auth/drivers/auth/bearer";
import axios from "@websanova/vue-auth/drivers/http/axios.1.x";
import router from "@websanova/vue-auth/drivers/router/vue-router.2.x";
// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: "e-learning",
  rolesKey: "role",
  registerData: { url: "v1/auth/register", method: "POST", redirect: "/auth/login" },
  loginData: {
    url: "v1/auth/login",
    method: "POST",
    redirect: "/student",
    fetchUser: true
  },
  logoutData: {
    url: "v1/auth/logout",
    method: "POST",
    redirect: "/auth/login",
    makeRequest: true
  },
  fetchData: { url: "v1/account/me", method: "GET", enabled: true },
  refreshData: {
    url: "v1/auth/refresh",
    method: "GET",
    enabled: true,
    interval: 30
  }
};
export default config;
