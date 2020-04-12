<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        User
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <ListUser />
      </div>
    </div>
  </div>
</template>
<script>
import ListUser from "../../components/admin/ListUser.vue";
export default {
  mounted() {
    //
  },
  components: {
    ListUser
  },
  mounted() {
  }
};
</script>
