<template>
  <div>
    <div>List of User Course</div>
    <div class="alert alert-danger" v-if="has_error">
      <p>An error on server</p>
    </div>
    <table class="table">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Course</th>
        <th scope="col">Description</th>
        <th scope="col">Action</th>
      </tr>
      <tr
        v-for="data in courses"
        v-bind:key="data.id"
        style="margin-bottom: 5px;"
      >
        <th scope="row">{{ data.id }}</th>
        <td>{{ data.course.title }}</td>
        <td>{{ data.course.description }}</td>
        <td>
          <a href="#" class="btn btn-sm btn-primary">Learn</a>
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      has_error: false,
      courses: null
    };
  },
  mounted() {
    this.getCourseUsers();
  },
  methods: {
    getCourseUsers() {
      this.$http({
        url: `/v1/user/course`,
        method: "GET"
      }).then(
        res => {
          this.courses = res.data;
        },
        () => {
          this.has_error = true;
        }
      );
    }
  }
};
</script>
