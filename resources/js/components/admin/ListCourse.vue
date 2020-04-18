<template>
  <div>
    <div class="alert alert-danger" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <b-table hover :items="courses.data" :fields="fields">
      <template v-slot:cell(status)="{ item }">
        <span v-if="item.status" class="badge badge-info">Published</span>
        <span v-else class="badge badge-warning">Draft</span>
      </template>
      <template v-slot:cell(actions)="{ item }">
        <b-button
          size="sm"
          variant="outline-primary"
          @click="$router.push(`/dashboard/courses/editor/${item.id}`)"
        >
          Update
        </b-button>
      </template>
    </b-table>
    <div class="row justify-content-center">
      <b-pagination
        v-model="courses.current_page"
        :total-rows="courses.total"
        :per-page="courses.per_page"
        aria-controls="my-table"
        @change="getCourses($event)"
      />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      errorMessage: false,
      fields: ["id", "title", "status", "updated_at", "actions"],
      courses: {}
    };
  },
  mounted() {
    this.getCourses();
  },
  methods: {
    getCourses(page) {
      this.$http({
        url: `/v1/master/course?page=${page || 1}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.courses = data;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    }
  }
};
</script>
