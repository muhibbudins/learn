<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              Dashboard Analytics
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
