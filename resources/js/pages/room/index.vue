<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        Class Room
      </div>
    </div>
    <div v-if="!is_loading" class="timeline">
      <div
        class="timeline-start state-completed"
        :class="{
          'state-current': detectPage(
            false,
            param_course,
            param_user_course,
            0,
            'course',
            0
          )
        }"
        @click="
          detectPage(true, param_course, param_user_course, 0, 'course', 0)
        "
      >
        <HomeIcon />
      </div>
      <div
        class="timeline-module"
        v-for="(modules, moduleId) in status.modules"
        :key="`modules-${moduleId}`"
      >
        <div
          class="timeline-module_state"
          v-for="(lessons, lessonId) in modules.lessons"
          :key="`lessons-${lessonId}`"
          :class="{
            'state-completed': lessons.completed,
            'state-current': detectPage(
              false,
              param_course,
              param_user_course,
              moduleId,
              'lessons',
              lessonId
            )
          }"
          @click="
            detectPage(
              true,
              param_course,
              param_user_course,
              moduleId,
              'lessons',
              lessonId
            )
          "
        >
          L{{ lessonId }}
        </div>
        <div
          class="timeline-module_state"
          v-for="(quizzes, quizId) in modules.quizzes"
          :key="`quizzes-${quizId}`"
          :class="{
            'state-completed': quizzes.completed,
            'state-current': detectPage(
              false,
              param_course,
              param_user_course,
              moduleId,
              'quizzes',
              quizId
            )
          }"
          @click="
            detectPage(
              true,
              param_course,
              param_user_course,
              moduleId,
              'quizzes',
              quizId
            )
          "
        >
          Q{{ quizId }}
        </div>
      </div>
      <div
        class="timeline-end"
        :class="{
          'state-completed': status.completed,
          'state-current': detectPage(
            false,
            param_course,
            param_user_course,
            1,
            'finish',
            1
          )
        }"
        @click="
          detectPage(true, param_course, param_user_course, 1, 'finish', 1)
        "
      >
        <AwardIcon />
      </div>
    </div>
    <div v-if="!is_loading">
      <DetailCourse v-if="param_type == 'course'" :content="content" />
      <DetailLesson
        v-if="param_type == 'lessons'"
        :content="content"
        :status="status.modules[param_module][param_type]"
      />
      <DetailQuiz
        v-if="param_type == 'quizzes'"
        :content="content"
        :status="status.modules[param_module][param_type]"
      />
      <DetailFinish v-if="param_type == 'finish'" :status="status" />
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
      content: {},
      status: {},
      param_course: 0,
      param_user_course: 0,
      param_module: 0,
      param_type: 0,
      param_content: 0,
      is_loading: true
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
    this.is_loading = true;
    this.loadContent();
  },
  watch: {
    $route(to, from) {
      this.is_loading = true;
      this.loadContent();
    }
  },
  methods: {
    async loadContent() {
      const {
        params: { course_id, user_course_id, module_id, type, content_id }
      } = this.$route;

      this.param_course = course_id;
      this.param_user_course = user_course_id;
      this.param_module = module_id;
      this.param_type = type;
      this.param_content = content_id;

      const resultStatus = await this.loadStatus(user_course_id, course_id);

      if (resultStatus.data) {
        this.status = resultStatus.data;
      }

      switch (type) {
        case "course":
          const resultCourse = await this.loadCourse(user_course_id, course_id);
          if (resultCourse.data) {
            this.content = resultCourse.data;
          }
          break;
        case "lessons":
          const resultLesson = await this.loadLesson(
            user_course_id,
            content_id
          );
          if (resultLesson.data) {
            this.content = resultLesson.data;
          }
          break;
        case "quizzes":
          const resultQuiz = await this.loadQuiz(user_course_id, content_id);
          if (resultQuiz.data) {
            this.content = resultQuiz.data;
          }
          break;
      }

      this.is_loading = false;
    },
    async loadCourse(user_course_id, course_id) {
      const { data } = await this.$http({
        url: `/v1/room/course/${user_course_id}/${course_id}`,
        method: "GET"
      });

      return data;
    },
    async loadLesson(user_course_id, content_id) {
      const { data } = await this.$http({
        url: `/v1/room/lesson/${user_course_id}/${content_id}`,
        method: "GET"
      });

      return data;
    },
    async loadQuiz(user_course_id, content_id) {
      const { data } = await this.$http({
        url: `/v1/room/quiz/${user_course_id}/${content_id}`,
        method: "GET"
      });

      return data;
    },
    async loadStatus(user_course_id, course_id) {
      const { data } = await this.$http({
        url: `/v1/room/${user_course_id}/${course_id}`,
        method: "GET"
      });

      return data;
    },
    createPage(course_id, user_course_id, module_id, type, content_id) {
      const { path } = this.$route;
      const nextPage = `/room/${course_id}/${user_course_id}/${module_id}/${type}/${content_id}`;

      return {
        current: path,
        next_page: nextPage
      };
    },
    detectPage(
      forRouter,
      course_id,
      user_course_id,
      module_id,
      type,
      content_id = 0
    ) {
      const { current, next_page } = this.createPage(
        course_id,
        user_course_id,
        module_id,
        type,
        content_id
      );

      if (current !== next_page) {
        if (forRouter) {
          this.$router.push(next_page);
        } else {
          return false;
        }
      }

      return true;
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
    &:hover {
      background-color: rgba($color: #1a1a1a, $alpha: 0.2);
    }
    &.state-completed {
      background-color: #1a1a1a;
      color: #ffffff;
    }
    &.state-current {
      background-color: rgb(53, 34, 231);
      color: #ffffff;
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
