<template>
  <div class="container">
    <div class="card card-default">
      <div class="card-body">
        <div>User Reporting Page</div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  mounted() {
    //
  },
  components: {
  },
  mounted() {
    this.$auth.load().then(() => {
      const { role } = this.$auth.check() && this.$auth.user()

      if (role === "admin") {
        this.$router.push({ name: "dashboard" });
      }
    })
  }
};
</script>
