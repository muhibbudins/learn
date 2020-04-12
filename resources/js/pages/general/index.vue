<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div>Latest Courses</div>
      </div>
    </div>
    <div class="row">
      <div class="col-4" v-for="course in courses" :key="course.id">
        <CardCourse :course="course" />
      </div>
    </div>
  </div>
</template>

<script>
import CardCourse from "../../components/CardCourse.vue";

export default {
  data() {
    return {
      courses: []
    };
  },
  components: {
    CardCourse
  },
  mounted() {
    this.getCourses();
  },
  methods: {
    getCourses() {
      this.$http({
        url: `/v1/general/course`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.courses = data.data;
        }
      );
    }
  }
};
</script>
