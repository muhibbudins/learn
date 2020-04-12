<template>
  <div class="container">
    <div class="card card-default">
      <div class="card-body">
        <div>Latest Courses</div>
        <ListCourse />
      </div>
    </div>
  </div>
</template>

<script>
import ListCourse from "../components/student/ListCourse.vue";

export default {
  data() {
    return {
      //
    };
  },
  components: {
    ListCourse
  },
  mounted() {
    this.$auth.load().then(() => {
      const { role } = this.$auth.check() && this.$auth.user()

      if (role === "admin") {
        this.$router.push({ name: "dashboard" });
      }
    })
  }
};
</script>
