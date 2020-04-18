<template>
  <section>
    <b-form-group id="fieldset-1" label="Course Title" label-for="input-1">
      <b-form-input id="input-1" v-model="course.title" />
    </b-form-group>
    <b-form-group
      id="fieldset-1"
      label="Course Description"
      label-for="input-1"
    >
      <b-form-textarea
        v-model="course.description"
        placeholder="Enter something..."
        rows="3"
        max-rows="6"
      />
    </b-form-group>
    <b-form-group id="fieldset-1" label="Course Content" label-for="input-1">
      <Editor :content="course.content" @contentChange="onContentChange" />
    </b-form-group>
    <b-button class="mr-3" variant="success" @click="saveCourse">
      Save Change
    </b-button>
    <b-button
      v-if="!course.status"
      class="mr-3"
      variant="danger"
      @click="deleteCourse"
    >
      Delete
    </b-button>
    <b-button @click="$router.back()">
      Cancel
    </b-button>
  </section>
</template>

<script>
import Editor from "../Editor";

export default {
  props: {
    course: {
      type: [Object, Array],
      default: () => {}
    }
  },
  data() {
    return {
      lessonDataContent: ""
    };
  },
  components: {
    Editor
  },
  methods: {
    onContentChange(value) {
      this.lessonDataContent = value;
    },
    saveCourse() {
      this.course.content = this.lessonDataContent;
      this.$http({
        url: `/v1/master/course/update/${this.course.id}`,
        method: "POST",
        data: this.course
      }).then(({ data }) => {
        //
      });
    },
    deleteCourse() {
      this.$http({
        url: `/v1/advanced/course/delete/${this.course.id}`,
        method: "DELETE"
      }).then(() => {
        return this.$router.back();
      });
    }
  }
};
</script>
