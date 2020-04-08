<template>
  <div>
    <div>List of User Course</div>
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
        v-for="courseuser in courseusers"
        v-bind:key="courseuser.id"
        style="margin-bottom: 5px;"
      >
        <th scope="row">{{ courseuser.id }}</th>
        <td>{{ courseuser.name }}</td>
        <td>{{ courseuser.email }}</td>
        <td>{{ courseuser.created_at }}</td>
      </tr>
    </table>
  </div>
</template>
<script>
export default {
  data() {
    return {
      has_error: false,
      courseusers: null
    };
  },
  mounted() {
    this.getCourseUsers();
  },
  methods: {
    getCourseUsers() {
      this.$http({
        url: `/v1/courseuser`,
        method: "GET"
      }).then(
        res => {
          this.courseusers = res.data.courseusers;
        },
        () => {
          this.has_error = true;
        }
      );
    }
  }
};
</script>
