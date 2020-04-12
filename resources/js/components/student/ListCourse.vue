<template>
  <div class="row">
    <div class="col-4" v-for="course in courses" :key="course.id">
      <CardCourse :course="course" />
    </div>
  </div>
</template>

<script>
import CardCourse from './CardCourse'

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
