<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              Profile
            </h6>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <div>Name : {{ profile.name }}</div>
        <div>Email : {{ profile.email }}</div>
        <div>Address : {{ profile.address || "-" }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      profile: {}
    };
  },
  mounted() {
    this.$auth.load().then(() => {
      this.profile = this.$auth.user();
    });
  }
};
</script>
