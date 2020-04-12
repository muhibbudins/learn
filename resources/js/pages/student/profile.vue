<template>
  <div class="container">
    <div class="card card-default">
      <div class="card-body">
        <h4>Profile</h4>
        <div>Name : {{ profile.name }}</div>
        <div>Email : {{ profile.email }}</div>
        <div>Address : {{ profile.address || '-'}}</div>
        <div>My Courses : </div>
        <div v-for="data in profile.courses" :key="data.id">
          <div>Title : {{ data.course.title }}</div>
          <div>Description : {{ data.course.description }}</div>
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
      console.log('--this.profile', this.profile)
    })
  },
  methods: {
  }
};
</script>
