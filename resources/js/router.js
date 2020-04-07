const VueRouter = require("vue-router").default;

// Pages
const Home = require("./pages/Home").default;
const Register = require("./pages/Register").default;
const Login = require("./pages/Login").default;
const Course = require("./pages/Course").default;
const Dashboard = require("./pages/admin/Dashboard").default;
const DashboardCourse = require("./pages/admin/Course").default;
const DashboardUserCourse = require("./pages/admin/UserCourse").default;
const DashboardUser = require("./pages/admin/User").default;

const authAdmin = {
  auth: {
    roles: "admin",
    redirect: { name: "login" },
    forbiddenRedirect: { name: "home" },
  }
}

// Routes
const routes = [
  {
    path: "/",
    name: "home",
    component: Home
  },
  {
    path: "/courses",
    name: "courses",
    component: Course,
    meta: {
      auth: true
    }
  },
  {
    path: "/register",
    name: "register",
    component: Register
  },
  {
    path: "/login",
    name: "login",
    component: Login
  },
  {
    path: "/dashboard",
    name: "dashboard",
    component: Dashboard,
    meta: {
      ...authAdmin
    }
  },
  {
    path: "/dashboard/courses",
    name: "dashboard-courses",
    component: DashboardCourse,
    meta: {
      ...authAdmin
    }
  },
  {
    path: "/dashboard/user-course",
    name: "dashboard-user-course",
    component: DashboardUserCourse,
    meta: {
      ...authAdmin
    }
  },
  {
    path: "/dashboard/user",
    name: "dashboard-user",
    component: DashboardUser,
    meta: {
      ...authAdmin
    }
  }
];
const router = new VueRouter({
  history: true,
  mode: "history",
  routes
});
module.exports = router;
