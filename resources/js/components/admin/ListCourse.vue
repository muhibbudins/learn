<template>
  <div>
    <div>List of Course</div>
    <div class="alert alert-danger" v-if="has_error">
      <p>An error on server</p>
    </div>
    <table class="table">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Nom</th>
        <th scope="col">Email</th>
        <th scope="col">Date d'inscription</th>
      </tr>
      <tr
        v-for="course in courses"
        v-bind:key="course.id"
        style="margin-bottom: 5px;"
      >
        <th scope="row">{{ course.id }}</th>
        <td>{{ course.name }}</td>
        <td>{{ course.email }}</td>
        <td>{{ course.created_at }}</td>
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
    this.getCourses();
  },
  methods: {
    getCourses() {
      this.$http({
        url: `/v1/course`,
        method: "GET"
      }).then(
        res => {
          this.courses = res.data.courses;
        },
        () => {
          this.has_error = true;
        }
      );
    }
  }
};
</script>
