<template>
  <section>
    <div class="row align-items-center mb-3">
      <div class="col-4">
        <b-form-select
          v-model="moduleSelected"
          :options="moduleOptions"
          @change="changeModule"
        />
      </div>
      <div class="col-4">
        <b-form-select
          v-model="lessonSelected"
          :options="lessonOptions"
          @change="changeLesson"
        />
      </div>
      <div class="col-1">
        <h5 class="mb-0 text-center text-muted">OR</h5>
      </div>
      <div class="col-3">
        <b-button variant="primary" @click="createLesson">
          Create New
        </b-button>
      </div>
    </div>
    <div v-if="lessonData">
      <b-form-group id="fieldset-1" label="lesson Title" label-for="input-1">
        <b-form-input
          id="input-1"
          aria-describedby="input-live-help input-live-feedback"
          :state="lessonState.title"
          v-model="lessonData.title"
        />
        <b-form-invalid-feedback id="input-live-feedback">
          Enter at least 3 letters
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group
        id="fieldset-1"
        label="lesson Description"
        label-for="input-1"
      >
        <b-form-textarea
          aria-describedby="input-live-help input-live-feedback"
          :state="lessonState.description"
          v-model="lessonData.description"
          placeholder="Enter something..."
          rows="3"
          max-rows="6"
        />
        <b-form-invalid-feedback id="input-live-feedback">
          Enter at least 3 letters
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group id="fieldset-1" label="Course Content" label-for="input-1">
        <Editor
          v-if="isLoadedState"
          :content="lessonData.content"
          @contentChange="onContentChange"
        />
      </b-form-group>
      <b-button class="mr-3" variant="success" @click="saveLesson" :disabled="isLoading">
        <span v-if="!isLoading">Save Change</span>
        <b-spinner v-else small></b-spinner>
      </b-button>
      <b-button
        class="mr-3"
        v-if="!course.status && course.has_user === 0 && !isCreateAction"
        variant="danger"
        @click="deleteLesson"
      >
        Delete
      </b-button>
      <b-button @click="$router.back()">
        Cancel
      </b-button>
    </div>
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
  components: {
    Editor
  },
  data() {
    return {
      isLoading: false,
      isLoadedState: true,
      isCreateAction: false,
      moduleSelected: null,
      moduleOptions: [],
      lessonData: null,
      lessonSelected: null,
      lessonDataContent: "",
      lessonOptions: [
        {
          value: null,
          text: "- Lesson is Empty -"
        }
      ],
      lessonState: {
        title: null,
        description: null
      }
    };
  },
  mounted() {
    if (this.course.modules) {
      this.moduleOptions = [
        { value: null, text: "Select Module" },
        ...this.course.modules
      ];
    }
  },
  methods: {
    updateCombobox(lessonData, isDelete) {
      let deletedIndex = null;

      this.lessonOptions.map((data, index) => {
        if (data.value === lessonData.id) {
          data.text = lessonData.title;

          if (isDelete) {
            deletedIndex = index;
          }
        }

        return data;
      });

      if (deletedIndex) {
        this.lessonOptions.splice(deletedIndex, 1);
      }
    },
    changeModule() {
      if (this.moduleSelected) {
        if (this.course.lessons) {
          this.lessonOptions = [
            { value: null, text: "Select Lesson" },
            ...this.course.lessons[this.moduleSelected]
          ];
        }
      }
    },
    changeLesson() {
      this.isLoadedState = false;

      if (this.lessonSelected) {
        this.isCreateAction = false;
        this.$http({
          url: `/v1/master/module/lesson?entity=${this.lessonSelected}`,
          method: "GET"
        }).then(({ data }) => {
          this.lessonData = data;
          setTimeout(() => {
            this.isLoadedState = true;
          }, 500);
        });
      } else {
        this.isCreateAction = true;
        setTimeout(() => {
          this.isLoadedState = true;
        }, 500);
      }
    },
    createLesson() {
      if (!this.moduleSelected) {
        return;
      }

      this.isLoadedState = false;
      this.isCreateAction = true;
      this.lessonSelected = null;
      this.lessonData = {
        module_id: this.moduleSelected,
        title: "",
        description: "",
        content: ""
      };
      setTimeout(() => {
        this.isLoadedState = true;
      }, 500);
    },
    onContentChange(value) {
      this.lessonDataContent = value;
    },
    saveLesson() {
      if (!this.moduleSelected) {
        return;
      }

      if (this.lessonData.title === "") {
        this.lessonState.title = false;
        return;
      }

      if (this.lessonData.description === "") {
        this.lessonState.description = false;
        return;
      }

      if (this.lessonDataContent) {
        this.lessonData.content = this.lessonDataContent;
      }

      this.isLoading = true

      if (this.isCreateAction) {
        this.$http({
          url: "/v1/master/module/lesson",
          method: "POST",
          data: this.lessonData
        }).then(
          ({
            data: {
              data: { id, title }
            }
          }) => {
            this.lessonOptions.push({
              value: id,
              text: title
            });
            this.lessonData = null;
            this.lessonSelected = null;
            this.isLoading = false
          }
        );
      } else {
        this.$http({
          url: `/v1/master/module/lesson/update/${this.lessonSelected}`,
          method: "POST",
          data: this.lessonData
        }).then(({ data }) => {
          this.updateCombobox(this.lessonData);
          this.lessonData = null;
          this.lessonSelected = null;
          this.isLoading = false
        });
      }
    },
    deleteLesson() {
      this.$http({
        url: `/v1/advanced/module/lesson/delete/${this.lessonSelected}`,
        method: "DELETE"
      }).then(() => {
        this.updateCombobox(this.lessonData, true);
        this.lessonData = null;
        this.lessonSelected = null;
      });
    }
  }
};
</script>
