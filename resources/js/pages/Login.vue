<template>
  <div class="container">
    <div class="card">
      <div class="card-body">
        <div class="alert alert-danger" v-if="has_error">
          Can't logged in, your username or password is invalid
        </div>
        <form autocomplete="off" @submit.prevent="login" method="post">
          <div class="form-group">
            <label for="email">E-mail</label>
            <input
              type="email"
              id="email"
              class="form-control"
              placeholder="user@example.com"
              v-model="email"
              required
            />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              class="form-control"
              v-model="password"
              required
            />
          </div>
          <button type="submit" class="btn btn-primary">
            Login
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      email: null,
      password: null,
      has_error: false
    };
  },
  mounted() {
    // get the redirect object
    var redirect = this.$auth.redirect();
    const redirectTo = redirect
      ? redirect.from.name
      : this.$auth.user().role === "admin"
      ? "dashboard"
      : "home";
  },
  methods: {
    login() {
      // get the redirect object
      var redirect = this.$auth.redirect();
      var app = this;
      this.$auth.login({
        params: {
          email: app.email,
          password: app.password
        },
        success: function() {
          // handle redirection
          const redirectTo = redirect
            ? redirect.from.name
            : this.$auth.user().role === "admin"
            ? "dashboard"
            : "home";
          this.$router.push({ name: redirectTo });
        },
        error: function() {
          app.has_error = true;
        },
        rememberMe: true,
        fetchUser: true
      });
    }
  }
};
</script>
