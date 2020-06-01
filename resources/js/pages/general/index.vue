<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div>My Courses</div>
      </div>
    </div>
    <div class="row">
      <div class="col-4" v-for="course in courses" :key="course.id">
        <CardRoom :user-course="course" />
      </div>
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
