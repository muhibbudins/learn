<template>
  <content>
    <navbar />
    <div class="container my-4">
      <router-view />
    </div>
  </content>
</template>
<script>
import navbar from "./components/Navbar.vue";
export default {
  data() {
    return {
      //
    };
  },
  components: {
    navbar
  }
};
</script>
