<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body text-center">
        <h6 class="mb-0">
          Profile
        </h6>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <div class="card card-default">
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-2 py-2">Username</div>
              <div class="col-10 py-2">
                {{ profile.name }}
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-2 py-2">Email</div>
              <div class="col-10 py-2">
                {{ profile.email }}
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-2 py-3">First Name</div>
              <div class="col-10 py-2">
                <b-form-input
                  v-model="profile.firstname"
                  placeholder="First Name"
                />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-2 py-3">Last Name</div>
              <div class="col-10 py-2">
                <b-form-input
                  v-model="profile.lastname"
                  placeholder="Last Name"
                />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-2 py-4">Address</div>
              <div class="col-10 py-2">
                <b-form-textarea
                  v-model="profile.address"
                  placeholder="Address"
                />
              </div>
            </div>
            <button class="btn btn-block btn-primary" @click="updateProfile">
              Update Profile
            </button>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card card-default mb-3">
          <div class="card-body text-center">
            <h6 class="mb-0">
              Change Password
            </h6>
          </div>
        </div>
        <div class="card card-default">
          <div class="card-body">
            <div class="row mb-2 mt-2">
              <div class="col-8">Old Password</div>
              <div class="col-12 py-2">
                <b-form-input
                  type="password"
                  v-model="login.password_old"
                  placeholder="First Name"
                />
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-8">New Password</div>
              <div class="col-12 py-2">
                <b-form-input
                  type="password"
                  v-model="login.password"
                  placeholder="First Name"
                />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-8">Confirm Password</div>
              <div class="col-12 py-2">
                <b-form-input
                  type="password"
                  v-model="login.password_confirmation"
                  placeholder="Last Name"
                />
              </div>
            </div>
            <button class="btn btn-block btn-danger" @click="updatePassword">
              Update Password
            </button>
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
      profile: {},
      login: {
        password_old: "",
        password: "",
        password_confirmation: ""
      },
      isEditState: false,
      isPasswordState: false
    };
  },
  mounted() {
    this.$auth.load().then(() => {
      this.profile = this.$auth.user();
    });
  },
  methods: {
    updatePassword() {
      const { id } = this.profile;
      const { password, password_old, password_confirmation } = this.login;

      this.$http({
        url: `/v1/account/update/${id}`,
        method: "POST",
        data: {
          password,
          password_old,
          password_confirmation
        }
      }).then(({ data }) => {
        this.$auth.logout({
          makeRequest: true,
          redirect: { name: "login" }
        });
      });
    },
    updateProfile() {
      const { id, firstname, lastname, address } = this.profile;

      this.$http({
        url: `/v1/account/update/${id}`,
        method: "POST",
        data: {
          firstname: firstname,
          lastname: lastname,
          address: address
        }
      }).then(({ data }) => {});
    }
  }
};
</script>

<style>
.cursor-pointer {
  cursor: pointer;
}
</style>
