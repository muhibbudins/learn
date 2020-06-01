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
    <b-button class="mr-3" variant="success" @click="saveCourse" :disabled="isLoading">
      <span v-if="!isLoading">Save Change</span>
      <b-spinner v-else small></b-spinner>
    </b-button>
    <b-button
      v-if="!course.status && course.has_user === 0"
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
      courseDataContent: "",
      isLoading: false,
    };
  },
  components: {
    Editor
  },
  methods: {
    onContentChange(value) {
      this.courseDataContent = value;
    },
    saveCourse() {
      if (this.courseDataContent) {
        this.course.content = this.courseDataContent;
      }
      
      this.isLoading = true;

      this.$http({
        url: `/v1/master/course/update/${this.course.id}`,
        method: "POST",
        data: this.course
      }).then(({ data }) => {
        this.isLoading = false
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
