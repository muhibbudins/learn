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
            <div v-if="course.content" v-html="course.content"></div>
            <div v-if="!($auth.user() && $auth.user().role === 'admin')">
              <button
                v-if="!alreadyJoined"
                class="btn btn-primary"
                @click="joinCourse"
              >
                Join Course
              </button>
              <button
                v-else
                class="btn btn-primary"
                @click="
                  $router.push(
                    `/room/${userCourse.course_id}/${userCourse.id}/0/course/0`
                  )
                "
              >
                Open Class
              </button>
            </div>
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
export default {
  data() {
    return {
      course: {},
      alreadyJoined: 0,
      course_id: 0,
      userCourse: {}
    };
  },
  mounted() {
    const { id } = this.$route.params;
    this.course_id = id;

    this.getCourses(id);

    if (this.$auth.check()) {
      this.getUserCourse(this.$auth.user().id);
    }
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
    getUserCourse(user_id) {
      this.$http({
        url: `/v1/account/course`,
        method: "GET",
        params: {
          user_id,
          course_id: this.course_id
        }
      }).then(({ data }) => {
        if (data.data) {
          this.userCourse = data.data;
          this.alreadyJoined = true;
        }
      });
    },
    joinCourse() {
      if (this.$auth.check()) {
        const { id } = this.$auth.user();
        this.$http({
          url: `/v1/account/course/join`,
          method: "POST",
          data: {
            course_id: this.course_id.toString(),
            user_id: id.toString()
          }
        }).then(({ data }) => {
          this.alreadyJoined = true;
          this.getUserCourse(id);
        });
      } else {
        this.$router.push({ name: "login", query: { ref: this.$route.path } });
      }
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
