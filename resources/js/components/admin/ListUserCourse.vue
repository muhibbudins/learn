<template>
  <div>
    <div class="alert alert-danger" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <div class="row justify-content-end">
      <b-pagination
        v-model="usercourses.current_page"
        :total-rows="usercourses.total"
        :per-page="usercourses.per_page"
        aria-controls="my-table"
        @change="getUserCourses($event)"
      />
    </div>
    <b-table hover :items="usercourses.data" :fields="fields" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      errorMessage: false,
      fields: [
        {
          key: "user.name",
          label: "Username"
        },
        {
          key: "user.email",
          label: "E-mail"
        },
        {
          key: "course.title",
          label: "Course Title"
        },
        {
          key: "created_at",
          label: "Join Date"
        }
      ],
      usercourses: {}
    };
  },
  mounted() {
    this.getUserCourses();
  },
  methods: {
    getUserCourses(page) {
      this.$http({
        url: `/v1/master/user/course?page=${page || 1}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.usercourses = data;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    }
  }
};
</script>
