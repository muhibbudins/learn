<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body text-center">
        <h6 class="mb-0">
          My Course
        </h6>
      </div>
    </div>
    <div v-if="courses.length > 0" class="row">
      <div class="col-4" v-for="course in courses" :key="course.id">
        <CardRoom :user-course="course" />
      </div>
    </div>
    <div v-else class="text-center mt-5">
      <em>No course taked</em>
    </div>
  </div>
</template>

<script>
import CardRoom from "../../components/CardRoom.vue";

export default {
  data() {
    return {
      courses: []
    };
  },
  components: {
    CardRoom
  },
  mounted() {
    this.getCourses();
  },
  methods: {
    getCourses() {
      this.$http({
        url: `/v1/account/course`,
        method: "GET"
      }).then(({ data }) => {
        this.courses = data.data;
      });
    }
  }
};
</script>
