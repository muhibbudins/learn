<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              User Courses
            </h6>
          </div>
          <div class="col-4 ml-auto text-right">
            <b-button size="sm" variant="secondary">
              &nbsp;
            </b-button>
          </div>
        </div>
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
  components: {
    ListUserCourse
  }
};
</script>
