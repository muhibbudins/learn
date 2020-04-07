<template>
  <div class="container">
    <div class="card card-default">
      <div class="card-body">
        Halaman Depan
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      //
    };
  },
  components: {
    //
  },
  mounted() {
    if (this.$auth && this.$auth.user().role === "admin") {
      this.$router.push({ name: "dashboard" });
    }
  }
};
</script>
