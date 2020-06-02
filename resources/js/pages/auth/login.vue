<template>
  <div class="container">
    <div class="row">
      <div class="col-4 mx-auto">
        <div class="card card-default mb-3">
          <div class="card-body">
            Login Page
          </div>
        </div>
        <div class="card card-default">
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
                  placeholder="password"
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
      email: "",
      password: "",
      has_error: false
    };
  },
  methods: {
    login() {
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
          () => {
            const { ref } = this.$route.query
            if (ref) {
              this.$router.push(ref)
            } else {
              this.$router.push({ name: 'student-profile' })
            }
          },
          () => {
            app.has_error = true;
          }
        );
    }
  }
};
</script>
