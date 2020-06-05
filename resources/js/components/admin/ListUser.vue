<template>
  <div>
    <div class="alert alert-danger" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <div class="row">
      <div class="col-12 col-md-6">
        <b-form-input
          v-model="keyword"
          placeholder="Search user ..."
          class="mb-3"
        />
      </div>
      <div class="col-12 col-md-6">
        <b-pagination
          v-model="users.current_page"
          :total-rows="users.total"
          :per-page="users.per_page"
          aria-controls="my-table"
          class="float-right"
          @change="getUsers($event)"
        />
      </div>
    </div>
    <b-table hover :items="users.data" :fields="fields">
      <template v-slot:cell(actions)="{ item }">
        <b-button
          size="sm"
          variant="outline-primary"
          @click="$router.push(`/dashboard/user/editor/${item.id}`)"
        >
          Update
        </b-button>
        <b-button
          size="sm"
          variant="outline-danger"
          :disabled="item.id === 1"
          @click="deleteUser(item.id)"
        >
          Remove
        </b-button>
      </template>
    </b-table>
  </div>
</template>

<script>
import { debounce } from '../../helpers'

export default {
  data() {
    return {
      errorMessage: false,
      fields: ["name", "email", "role", "actions"],
      users: {},
      keyword: ""
    };
  },
  mounted() {
    this.getUsers();
  },
  watch: {
    keyword: debounce(function(newKeyword) {
      this.getUsers(false, newKeyword)
    }, 500)
  },
  methods: {
    getUsers(page, keyword) {
      this.$http({
        url: `/v1/master/user?page=${page || 1}&keyword=${keyword || ''}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.users = data;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    },
    deleteUser(id) {
      this.$http({
        url: `/v1/advanced/user/delete/${id}`,
        method: "DELETE"
      }).then(({ data }) => {
        this.getUsers(1);
      });
    }
  }
};
</script>
