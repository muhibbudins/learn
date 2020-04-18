<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body button-back" @click="$router.go(-1)">
        &lt; Back to list
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <div class="row">
          <div class="col-7">
            <h6 class="text-muted">About</h6>
            <h4>{{ course.title }}</h4>
            <p>{{ course.created_at }}</p>
            <div
              v-if="course.content"
              v-html="compileToHTML(course.content)"
            ></div>
            <button
              class="btn btn-primary"
              @click="$router.push({ name: $auth.check() ? '' : 'login' })"
            >
              Join Course
            </button>
          </div>
          <div class="col-5">
            <h6 class="text-muted">Table Of Contents:</h6>
            <ol v-if="course.modules">
              <li v-for="modules in course.modules" :key="modules.id">
                <p>{{ modules.title }}</p>
                <p>{{ modules.description }}</p>
                <div v-if="modules.lessons.length > 0">
                  <h6 class="text-muted">Lessons:</h6>
                  <ul>
                    <li v-for="lessons in modules.lessons" :key="lessons.id">
                      <p>{{ lessons.title }}</p>
                      <p>{{ lessons.description }}</p>
                    </li>
                  </ul>
                </div>
                <div v-else>This module doesn't have lessons</div>

                <div v-if="modules.quizzes.length > 0">
                  <h6 class="text-muted">Quizzes:</h6>
                  <ul>
                    <li v-for="quizzes in modules.quizzes" :key="quizzes.id">
                      <p>{{ quizzes.title }}</p>
                      <p>{{ quizzes.description }}</p>
                    </li>
                  </ul>
                </div>
                <div v-else>This module doesn't have quizzes</div>
              </li>
              <li>Finish</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import markdown from "marked";

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
      }).then(({ data }) => {
        this.course = data.data;
      });
    },
    compileToHTML(text) {
      return markdown(text, { sanitize: true });
    }
  }
};
</script>

<style lang="scss" scoped>
.button-back {
  cursor: pointer;
  transition: 0.2s ease;

  &:hover {
    background-color: rgba(0, 0, 0, 0.05);
  }
}
</style>
