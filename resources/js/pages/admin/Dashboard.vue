<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div>Dashboard</div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <Report />
      </div>
    </div>
  </div>
</template>
<script>
import Report from "../../components/admin/Report.vue";
export default {
  mounted() {
    //
  },
  components: {
    Report
  },
  mounted() {
  }
};
</script>
