const VueRouter = require("vue-router").default;

// Pages
const Home = require("./pages/home").default;
const Register = require("./pages/auth/register").default;
const Login = require("./pages/auth/login").default;
const Course = require("./pages/course/index").default;
const CourseDetail = require("./pages/course/detail").default;
const Profile = require("./pages/profile").default;
const Dashboard = require("./pages/admin/dashboard").default;
const DashboardCourse = require("./pages/admin/course").default;
const DashboardProfile = require("./pages/admin/profile").default;
const DashboardUser = require("./pages/admin/user").default;
const DashboardUserCourse = require("./pages/admin/user-course").default;

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
    path: "/course/detail/:id",
    name: "course-detail",
    component: CourseDetail
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
    path: "/profile",
    name: "profile",
    component: Profile,
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
    path: "/dashboard/profile",
    name: "dashboard-profile",
    component: DashboardProfile,
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
  },
  {
    path: "/dashboard/user-course",
    name: "dashboard-user-course",
    component: DashboardUserCourse,
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
