const bearer = require("@websanova/vue-auth/drivers/auth/bearer");
const axios = require("@websanova/vue-auth/drivers/http/axios.1.x");
const router = require("@websanova/vue-auth/drivers/router/vue-router.2.x");
// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: "laravel-vue-spa",
  tokenStore: ["localStorage"],
  rolesVar: "role",
  registerData: { url: "v1/register", method: "POST", redirect: "/login" },
  loginData: {
    url: "v1/login",
    method: "POST",
    redirect: "/",
    fetchUser: true
  },
  logoutData: {
    url: "v1/logout",
    method: "POST",
    redirect: "/login",
    makeRequest: true
  },
  fetchData: { url: "v1/me", method: "GET", enabled: true },
  refreshData: {
    url: "v1/refresh",
    method: "GET",
    enabled: true,
    interval: 30
  }
};
module.exports = config;
