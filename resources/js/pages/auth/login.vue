<template>
  <div class="container">
    <div class="row">
      <div class="col-4 mx-auto">
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
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      email: 'admin@e-learning.com',
      password: 'admin',
      has_error: false
    };
  },
  methods: {
    login() {
      // get the redirect object
      var app = this;
      this.$auth
        .login({
          params: {
            email: app.email,
            password: app.password
          },
          staySignedIn: true,
          fetchUser: true
        })
        .then(
          function() {},
          function() {
            app.has_error = true;
          }
        );
    }
  }
};
</script>
