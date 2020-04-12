<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div>Courses</div>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <div class="card card-default">
          <div class="card-body">
            <Sidebar />
          </div>
        </div>
      </div>
      <div class="col-8">
        <div class="card card-default">
          <div class="card-body">
            <ListCourse />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Sidebar from "../../components/admin/Sidebar.vue";
import ListCourse from "../../components/admin/ListCourse.vue";
export default {
  components: {
    Sidebar,
    ListCourse
  },
  mounted() {
  }
};
</script>
