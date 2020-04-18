<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              User Editor
            </h6>
          </div>
          <div class="col-4 ml-auto text-right">
            <b-button size="sm" variant="secondary">
              &nbsp;
            </b-button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <div class="form-group">
          <label for="input_name">Username</label>
          <input
            type="text"
            class="form-control"
            id="input_name"
            v-model="userData.name"
          />
        </div>
        <div class="form-group">
          <label for="input_email">Email address</label>
          <input
            type="email"
            class="form-control"
            id="input_email"
            v-model="userData.email"
            aria-describedby="emailHelp"
          />
          <small class="form-text text-muted">
            Write valid e-mail address
          </small>
        </div>
        <div class="form-group">
          <label for="input_password_1">Password</label>
          <input
            type="password"
            class="form-control"
            id="input_password_1"
            v-model="userData.password"
          />
          <small v-if="currentId" class="form-text text-muted">
            Only for update password
          </small>
        </div>
        <div class="form-group">
          <label for="input_password_2">Confirm Password</label>
          <input
            type="password"
            class="form-control"
            id="input_password_2"
            v-model="userData.password_confirmation"
          />
          <small v-if="currentId" class="form-text text-muted">
            Only for update password
          </small>
        </div>
        <b-button class="mr-3" variant="success" @click="saveUser">
          Save Change
        </b-button>
        <b-button @click="$router.back()">
          Cancel
        </b-button>
      </div>
    </div>
  </div>
</template>
<script>
import ListUser from "../../components/admin/ListUser.vue";
import { Editor } from "tiptap";
export default {
  data() {
    return {
      userData: {},
      currentId: 0
    };
  },
  components: {
    ListUser
  },
  mounted() {
    const {
      params: { id }
    } = this.$route;

    this.currentId = id;
    if (id) {
      this.$http({
        url: `/v1/master/user?entity=${id}`,
        method: "GET"
      }).then(({ data }) => {
        this.userData = data;
      });
    }
  },
  methods: {
    saveUser() {
      if (!this.currentId) {
        this.$http({
          url: "/v1/master/user",
          method: "POST",
          data: this.userData
        }).then(({ data }) => {
          this.$router.back();
        });
      } else {
        this.$http({
          url: `/v1/master/user/update/${this.currentId}`,
          method: "POST",
          data: this.userData
        }).then(({ data }) => {
          this.$router.back();
        });
      }
    }
  }
};
</script>
