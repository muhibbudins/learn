<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        User Course
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <ListUserCourse />
      </div>
    </div>
  </div>
</template>
<script>
import ListUserCourse from "../../components/admin/ListUserCourse.vue";
export default {
  mounted() {
    //
  },
  components: {
    ListUserCourse
  },
  mounted() {
  }
};
</script>
