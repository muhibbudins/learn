<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <h6 class="mb-0 text-center">
          Dashboard Analytics
        </h6>
      </div>
    </div>
    <Report />
  </div>
</template>
<script>
import Report from "../../components/admin/Report.vue";

export default {
  components: {
    Report
  }
};
</script>
