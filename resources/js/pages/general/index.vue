<template>
  <div class="container">
    <h5 class="my-4">Statistics</h5>
    <div class="row">
      <div class="col-4">
        <div class="card card-default">
          <div class="card-body">
            <h6 class="card-title text-muted text-center">Course Available</h6>
            <div class="text-center text-count">
              <CountTo
                :startVal="Math.random(0, 9)"
                :endVal="reportCourseTotal"
                :duration="3000"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card card-default">
          <div class="card-body">
            <h6 class="card-title text-muted text-center">Courses Taken</h6>
            <div class="text-center text-count">
              <CountTo
                :startVal="Math.random(0, 9)"
                :endVal="reportCourseTaken"
                :duration="3000"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card card-default">
          <div class="card-body">
            <h6 class="card-title text-muted text-center">Registered User</h6>
            <div class="text-center text-count">
              <CountTo
                :startVal="Math.random(0, 9)"
                :endVal="reportUserTotal"
                :duration="3000"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <h5 class="my-4">Available courses</h5>
    <div v-if="courses.length > 0" class="row">
      <div class="col-4" v-for="course in courses" :key="course.id">
        <CardCourse :course="course" />
      </div>
    </div>
    <div v-else class="text-center mt-5">
      <em>No course added</em>
    </div>
  </div>
</template>

<script>
import CountTo from "vue-count-to";
import CardCourse from "../../components/CardCourse.vue";

export default {
  data() {
    return {
      courses: [],
      reportCourseTotal: 0,
      reportCourseTaken: 0,
      reportUserTotal: 0
    };
  },
  components: {
    CardCourse,
    CountTo
  },
  mounted() {
    this.getCourses();
    this.loadTotalCourse();
    this.loadTotalUser();
  },
  methods: {
    loadTotalCourse() {
      this.$http({
        url: `/v1/report/course/total`,
        method: "GET"
      }).then(({ data }) => {
        this.reportCourseTotal = data.data.total
        this.reportCourseTaken = data.data.taken
      });
    },
    loadTotalUser() {
      this.$http({
        url: `/v1/report/user/total`,
        method: "GET"
      }).then(({ data }) => {
        this.reportUserTotal = data.data
      });
    },
    getCourses() {
      this.$http({
        url: `/v1/general/course`,
        method: "GET"
      }).then(({ data }) => {
        this.courses = data.data;
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.card-default {
  min-height: 160px;
}
.text-count {
  margin-top: 20px;
  font-size: 48px;
}
</style>
