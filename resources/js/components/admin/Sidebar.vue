<template>
  <div class="nav flex-column">
    <div
      v-for="(route, key) in routes"
      v-bind:key="route.path"
      class="nav-item"
    >
      <router-link
        class="nav-link"
        :to="{ name: route.path }"
        :key="key"
      >
        {{ route.name }}
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      routes: [
        {
          name: "Home",
          path: "home"
        },
        {
          name: "Courses",
          path: "courses"
        }
      ]
    };
  },
  mounted() {
    //
  }
};
</script>
