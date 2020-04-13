<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        Class Room
      </div>
    </div>
    <div class="timeline">
      <div
        class="timeline-start"
        @click="openPage(param_course, param_user_course, 'course', 0)"
      >
        <HomeIcon />
      </div>
      <div
        class="timeline-module"
        v-for="modules in course.modules"
        :key="`modules-${modules.id}`"
      >
        <div
          class="timeline-module_state"
          v-for="lessons in modules.lessons"
          :key="`lessons-${lessons.id}`"
          @click="openPage(param_course, param_user_course, 'lesson', lessons.id)"
        >
          L{{ lessons.id }}
        </div>
        <div
          class="timeline-module_state"
          v-for="quizzes in modules.quizzes"
          :key="`quizzes-${quizzes.id}`"
          @click="openPage(param_course, param_user_course, 'quiz', quizzes.id)"
        >
          Q{{ quizzes.id }}
        </div>
      </div>
      <div
        class="timeline-end"
        @click="openPage(param_course, param_user_course, 'finish', 1)"
      >
        <AwardIcon />
      </div>
    </div>
    <div class="card card-default mb-3">
      <div class="card-body">
        <DetailCourse v-if="param_type == 'course'" :content="course" />
        <DetailLesson v-if="param_type == 'lesson'" :content="content" />
        <DetailQuiz v-if="param_type == 'quiz'" :content="content" />
        <DetailFinish v-if="param_type == 'finish'"/>
      </div>
    </div>
    <div v-if="param_type != 'finish'" class="card card-default">
      <div class="card-body text-center">
        <button v-if="param_type != 'course'" class="btn btn-outline-primary">
          Previous Lesson
        </button>
        <button v-if="param_type != 'course'" class="btn btn-outline-primary">
          Mark as Completed
        </button>
        <button class="btn btn-outline-primary">
          Next Lesson
        </button>
      </div>
    </div>
    <div v-else class="card card-default">
      <div class="card-body text-center">
        <button class="btn btn-outline-primary">
          Open Certificate
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { HomeIcon, AwardIcon } from "vue-feather-icons";
import DetailCourse from "../../components/room/DetailCourse";
import DetailLesson from "../../components/room/DetailLesson";
import DetailQuiz from "../../components/room/DetailQuiz";
import DetailFinish from "../../components/room/DetailFinish";

export default {
  data() {
    return {
      course: {},
      content: {},
      status: {},
      param_user_course: 0,
      param_course: 0,
      param_type: 0,
      param_order: 0
    };
  },
  components: {
    HomeIcon,
    AwardIcon,
    DetailCourse,
    DetailLesson,
    DetailQuiz,
    DetailFinish
  },
  mounted() {
    this.loadContent()
  },
  watch: {
    '$route' (to, from) {
      this.loadContent()
    }
  },
  methods: {
    loadContent() {
      const {
        params: { course, user_course, type, order }
      } = this.$route;

      this.param_user_course = user_course;
      this.param_course = course;
      this.param_type = type;
      this.param_order = order;

      this.loadStatus(user_course, course);
      this.loadCourse(user_course, course);

      switch (type) {
        case "lesson":
          this.loadLesson(user_course, order);
          break;
        case "quiz":
          this.loadQuiz(user_course, order);
          break;
      }
    },
    loadCourse(user_course, course) {
      this.$http({
        url: `/v1/room/course/${user_course}/${course}`,
        method: "GET"
      }).then(({ data }) => {
        this.course = data.data;
      });
    },
    loadLesson(user_course, order) {
      this.$http({
        url: `/v1/room/lesson/${user_course}/${order}`,
        method: "GET"
      }).then(({ data }) => {
        this.content = data;
      });
    },
    loadQuiz(user_course, order) {
      this.$http({
        url: `/v1/room/quiz/${user_course}/${order}`,
        method: "GET"
      }).then(({ data }) => {
        this.content = data;
      });
    },
    loadStatus(user_course, course) {
      this.$http({
        url: `/v1/room/status/${user_course}/${course}`,
        method: "GET"
      }).then(({ data }) => {
        this.status = data.data;
      });
    },
    openPage(course, user_course, type, order) {
      const { path } = this.$route
      const nextPage = `/room/${course}/${user_course}/${type}/${order}`

      if (path !== nextPage) {
        this.$router.push(nextPage)
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.timeline {
  border: 1px solid #dedede;
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between;

  &-start,
  &-module_state,
  &-end {
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-right: 1px solid #dedede;
    border-left: 1px solid #dedede;
    display: flex;
    align-items: center;
    justify-content: center;

    svg {
      width: 14px;
    }
    &:hover,
    &.state-active {
      background-color: rgba($color: #000000, $alpha: 0.05);
    }
  }

  &-start {
    border-left: 0;
  }
  &-end {
    border-right: 0;
  }
  &-module {
    display: flex;
    margin: 0 10px;
    border-left: 1px solid #dedede;

    &_state {
      border-left: 0;
    }
  }
}
</style>
