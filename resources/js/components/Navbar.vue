<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
      E-Learning
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="navbar-nav ml-auto">
        <div
          v-for="(route, key) in routes[
            $auth.check('admin')
              ? 'admin'
              : $auth.check()
              ? 'student'
              : 'general'
          ]"
          v-bind:key="route.path"
        >
          <div class="nav-item">
            <router-link
              v-if="route.path"
              class="nav-link"
              :to="{ name: route.path }"
              :key="key"
            >
              {{ route.name }}
            </router-link>
            <a
              v-else-if="route.logout"
              class="nav-link"
              href="#"
              @click="loggingOut"
            >
              {{ route.name }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      loginAs: false,
      routes: {
        admin: [
          {
            name: "Dashboard",
            path: "dashboard"
          },
          {
            name: "Courses",
            path: "dashboard-courses"
          },
          {
            name: "User Course",
            path: "dashboard-user-course"
          },
          {
            name: "User",
            path: "dashboard-user"
          },
          {
            name: "Profile",
            path: "dashboard-profile"
          },
          {
            name: "Logout",
            logout: true
          }
        ],
        student: [
          {
            name: "Home",
            path: "home"
          },
          {
            name: "Courses",
            path: "courses",
            auth: true
          },
          {
            name: "Profile",
            path: "profile"
          },
          {
            name: "Logout",
            logout: true,
            auth: true
          }
        ],
        general: [
          {
            name: "Home",
            path: "home"
          },
          // {
          //   name: "Register",
          //   path: "register"
          // },
          {
            name: "Login",
            path: "login",
            auth: false
          }
        ]
      }
    };
  },
  methods: {
    loggingOut() {
      this.$auth.logout({
        makeRequest: true,
        redirect: { name: "login" }
      });
    }
  }
};
</script>
