<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div>Profile</div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <div>Name : {{ profile.name }}</div>
        <div>Email : {{ profile.email }}</div>
        <div>Address : {{ profile.address || '-'}}</div>
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
