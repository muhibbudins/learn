<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              Course Editor
            </h6>
          </div>
          <div class="col-4 ml-auto text-right">
            <b-button
              v-if="course.status"
              size="sm"
              variant="warning"
              @click="unPublishCourse"
            >
              Unpublish Course
            </b-button>
            <b-button v-else size="sm" variant="info" @click="publishCourse">
              Publish Course
            </b-button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-default">
      <div
        v-if="course.status"
        class="alert alert-info rounded-0 border-0 mb-0 text-center"
      >
        This course already published
      </div>
      <div
        v-else
        class="alert alert-warning rounded-0 border-0 mb-0 text-center"
      >
        This course still in draft
      </div>
      <b-tabs
        v-if="isLoaded"
        v-model="tabActive"
        content-class="mt-3"
        align="center"
      >
        <b-tab>
          <template slot="title">
            <h6 class="text-muted my-2">Course</h6>
          </template>
          <div class="card-body">
            <EditorCourse :course="course" />
          </div>
        </b-tab>
        <b-tab>
          <template slot="title">
            <h6 class="text-muted my-2">Module</h6>
          </template>
          <div class="card-body">
            <EditorModule :course="course" @need-update="updateCourse" />
          </div>
        </b-tab>
        <b-tab>
          <template slot="title">
            <h6 class="text-muted my-2">Module Lessons</h6>
          </template>
          <div class="card-body">
            <EditorLesson :course="course" @need-update="updateCourse" />
          </div>
        </b-tab>
        <b-tab>
          <template slot="title">
            <h6 class="text-muted my-2">Module Quizzes</h6>
          </template>
          <div class="card-body">
            <EditorQuiz :course="course" @need-update="updateCourse" />
          </div>
        </b-tab>
      </b-tabs>
    </div>
  </div>
</template>

<script>
import Course from "../../components/admin/Editor/Course.vue";
import Lesson from "../../components/admin/Editor/Lesson.vue";
import Module from "../../components/admin/Editor/Module.vue";
import Quiz from "../../components/admin/Editor/Quiz.vue";
import { Editor } from "tiptap";

export default {
  data() {
    return {
      course: {},
      courseId: 0,
      tabActive: 0,
      isLoaded: false
    };
  },
  components: {
    EditorCourse: Course,
    EditorLesson: Lesson,
    EditorModule: Module,
    EditorQuiz: Quiz
  },
  mounted() {
    const {
      params: { course_id }
    } = this.$route;
    this.courseId = course_id;
    this.getCourses(course_id);
  },
  methods: {
    unPublishCourse() {
      this.course.status = "0";
      this.$http({
        url: `/v1/master/course/update/${this.courseId}`,
        method: "POST",
        data: this.course
      }).then(({ data: { data } }) => {
        this.getCourses(this.courseId);
      });
    },
    publishCourse() {
      this.course.status = "1";
      this.$http({
        url: `/v1/master/course/update/${this.courseId}`,
        method: "POST",
        data: this.course
      }).then(({ data: { data } }) => {
        this.getCourses(this.courseId);
      });
    },
    updateCourse(index) {
      this.getCourses(this.courseId);
      this.tabActive = index;
    },
    getCourses(entity) {
      this.isLoaded = false;
      this.$http({
        url: `/v1/master/course?entity=${entity}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.course = data;
          this.isLoaded = true;
        },
        ({ message }) => {
          return this.$router.back();
        }
      );
    }
  }
};
</script>

<style lang="scss">
.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link {
  border-radius: 0;
  border-top: 0;
}
</style>
