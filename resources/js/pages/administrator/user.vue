<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              User Lists
            </h6>
          </div>
          <div class="col-4 ml-auto text-right">
            <b-button
              size="sm"
              variant="success"
              @click="$router.push('/dashboard/user/editor')"
            >
              Create New
            </b-button>
          </div>
        </div>
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
  components: {
    ListUser
  }
};
</script>
