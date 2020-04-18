<template>
  <div class="card" @click="$router.push(`/course/detail/${course.id}`)">
    <div class="card-body">
      <h4>{{ course.title }}</h4>
      <div class="mb-4">{{ course.description }}</div>
      <div class="text-muted">{{ course.created_at }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    course: {
      type: [Object, Array],
      default: () => {}
    }
  }
};
</script>

<style lang="scss" scoped>
.card {
  cursor: pointer;
  transition: 0.2s ease;

  &:hover {
    border-color: black;
  }
}
</style>
