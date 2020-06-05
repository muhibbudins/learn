<template>
  <div class="card card-default mb-3">
    <div class="card-body">
      <div v-if="status.completed" class="text-center">
        <em>
          <b>
            Certificate will be here
          </b>
        </em>
      </div>
      <div v-else class="text-center">
        <em>
          <b>
            You need to complete all lessons & quizzes to view the certificate
          </b>
        </em>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    status: {
      type: [Object, Array],
      default: () => {}
    }
  }
};
</script>
