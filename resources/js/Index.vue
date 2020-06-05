<template>
  <content>
    <navbar />
    <div class="container my-4">
      <router-view />
    </div>
    <notifications group="alert" position="bottom right" />
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
