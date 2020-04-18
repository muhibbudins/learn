<template>
  <div>
    <div class="alert alert-danger" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <b-pagination
      v-model="users.current_page"
      :total-rows="users.total"
      :per-page="users.per_page"
      aria-controls="my-table"
      @change="getUsers($event)"
    />
    <b-table hover :items="users.data" :fields="fields">
      <template v-slot:cell(actions)="{ item }">
        <b-button
          size="sm"
          variant="outline-primary"
          @click="$router.push(`/dashboard/user/editor/${item.id}`)"
        >
          Update
        </b-button>
      </template>
    </b-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      errorMessage: false,
      fields: ["id", "name", "email", "role", "actions"],
      users: {}
    };
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers(page) {
      this.$http({
        url: `/v1/master/user?page=${page || 1}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.users = data;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    }
  }
};
</script>
