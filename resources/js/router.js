const VueRouter = require("vue-router").default;

// Admin Pages
const Dashboard = require("./pages/administrator/dashboard").default;
const DashboardUser = require("./pages/administrator/user").default;
const DashboardUserEditor = require("./pages/administrator/user-editor").default;
const DashboardCourse = require("./pages/administrator/course").default;
const DashboardCourseEditor = require("./pages/administrator/course-editor").default;
const DashboardProfile = require("./pages/administrator/profile").default;
const DashboardUserCourse = require("./pages/administrator/user-course").default;

// Authentication
const Login = require("./pages/auth/login").default;
const Register = require("./pages/auth/register").default;

// General
const Home = require("./pages/general/index").default;
const Detail = require("./pages/general/detail").default;

// Room
const Room = require("./pages/room/index").default;

// Student
const StudentCourse = require("./pages/student/course").default;
const StudentProfile = require("./pages/student/profile").default;

const authAdmin = {
  auth: {
    roles: "admin",
    redirect: { name: "login" },
    forbiddenRedirect: { name: "home" }
  }
};

// Routes
const routes = [
  // Admin Pages
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
    path: "/dashboard/courses/editor/:course_id",
    name: "dashboard-courses-editor",
    component: DashboardCourseEditor,
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
    path: "/dashboard/user/editor/:id?",
    name: "dashboard-user-editor",
    component: DashboardUserEditor,
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

  // Authentication
  {
    path: "/auth/register",
    name: "register",
    component: Register
  },
  {
    path: "/auth/login",
    name: "login",
    component: Login
  },

  // General
  {
    path: "/",
    name: "home",
    component: Home
  },
  {
    path: "/course/detail/:id",
    name: "course-detail",
    component: Detail
  },

  // Room
  {
    path: "/room/:course_id/:user_course_id/:module_id/:type/:content_id",
    name: "room",
    component: Room,
    meta: {
      auth: true
    }
  },

  // Student
  {
    path: "/student/courses",
    name: "student-courses",
    component: StudentCourse,
    meta: {
      auth: true
    }
  },
  {
    path: "/student/profile",
    name: "student-profile",
    component: StudentProfile,
    meta: {
      auth: true
    }
  }
];
const router = new VueRouter({
  history: true,
  mode: "history",
  routes
});
module.exports = router;
