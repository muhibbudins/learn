<template>
  <div class="container">
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
            <h5>Admin Dashboard</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Sidebar from "../../components/Sidebar.vue";
export default {
  components: {
    Sidebar
  },
  mounted() {
  }
};
</script>
