<template>
  <div class="container">
    <div class="card card-default">
      <div class="card-body">
        <h4>{{ course.title }}</h4>
        <p>{{ course.description }}</p>
        <p>{{ course.content }}</p>
        <p>{{ course.created_at }}</p>
        <b>Modules</b>
        <ol v-if="course.modules">
          <li v-for="modules in course.modules" :key="modules.id">
            <p>{{ modules.title }}</p>
            <div v-if="modules.lessons.length > 0">
              <b>Lessons</b>
              <ol>
                <li v-for="lessons in modules.lessons" :key="lessons.id">
                  <p>{{ lessons.title }}</p>
                </li>
              </ol>
            </div>
            <div v-else>This module doesn't have lessons</div>

            <div v-if="modules.quizzes.length > 0">
              <b>Quizzes</b>
              <ol>
                <li v-for="quizzes in modules.quizzes" :key="quizzes.id">
                  <p>{{ quizzes.title }}</p>
                </li>
              </ol>
            </div>
            <div v-else>This module doesn't have quizzes</div>
          </li>
        </ol>
        <button class="btn btn-primary" @click="$router.push({ name: $auth.check() ? '' : 'login' })">
          Join Course
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      course: {}
    };
  },
  mounted() {
    const { id } = this.$route.params;
    this.getCourses(id);
  },
  methods: {
    getCourses(id) {
      this.$http({
        url: `/v1/general/course/detail/${id}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.course = data.data;
        }
      );
    }
  }
};
</script>
