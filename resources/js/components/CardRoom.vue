<template>
  <div class="card">
    <div class="card-body">
      <h4>{{ userCourse.course.title }}</h4>
      <div class="mb-3">{{ userCourse.course.description }}</div>
      <div class="text-muted mb-3">{{ userCourse.course.created_at }}</div>
      <button
        class="btn btn-primary btn-block"
        @click="$router.push(`/room/${userCourse.course_id}/${userCourse.course.id}/course/0`)"
      >
        Start Learning
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    userCourse: {
      type: [Object, Array],
      default: () => {}
    }
  }
}
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
