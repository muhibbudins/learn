<template>
  <b-navbar toggleable="lg" type="light" class="navbar navbar-expand-lg navbar-light bg-light">
    <b-container>
      <b-navbar-brand to="/">E-Learning</b-navbar-brand>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
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
      </b-collapse>
    </b-container>
  </b-navbar>
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
            name: "User",
            path: "dashboard-user"
          },
          {
            name: "User Course",
            path: "dashboard-user-course"
          },
          {
            name: "Profile",
            path: "profile"
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
            name: "My Courses",
            path: "student-courses",
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
          {
            name: "Register",
            path: "register"
          },
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
