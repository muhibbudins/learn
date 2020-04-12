<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div>Profile</div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <div class="row">
          <div class="col-2">Name</div>
          <div class="col-10">: {{ profile.name }}</div>
          <div class="col-2">Email</div>
          <div class="col-10">: {{ profile.email }}</div>
          <div class="col-2">Address</div>
          <div class="col-10">: {{ profile.address || '-'}}</div>
        </div>
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
  components: {
    //
  },
  mounted() {
    this.$auth.load().then(() => {
      this.profile = this.$auth.user()
    })
  },
  methods: {
  }
};
</script>
