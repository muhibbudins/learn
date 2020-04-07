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
      <div class="navbar-nav mr-auto">
        <div
          v-for="(route, key) in routes[
            $auth.check('admin') ? 'admin' : 'general'
          ]"
          v-bind:key="route.path"
        >
          <div
            class="nav-item"
            :class="{
              'd-none':
                !$auth.check('admin') &&
                (route.auth && !$auth.check()) ||
                ($auth.check() && route.auth === false)
            }"
          >
            <router-link
              v-if="route.path"
              class="nav-link"
              :to="{ name: route.path }"
              :key="key"
            >
              {{ route.name }}
            </router-link>
            <a
              v-else-if="route.action"
              class="nav-link"
              href="#"
              @click.prevent="route.action()"
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
            name: "Logout",
            action: this.$auth.logout
          }
        ],
        general: [
          {
            name: "Home",
            path: "home"
          },
          {
            name: "Courses",
            path: "courses",
            auth: true
          },
          // {
          //   name: "Register",
          //   path: "register"
          // },
          {
            name: "Login",
            path: "login",
            auth: false
          },
          {
            name: "Logout",
            action: this.$auth.logout,
            auth: true
          }
        ]
      }
    };
  },
  mounted() {
    //
  }
};
</script>
