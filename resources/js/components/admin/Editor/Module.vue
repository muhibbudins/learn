<template>
  <section>
    <div class="row align-items-center mb-3">
      <div class="col-7">
        <b-form-select
          v-model="moduleSelected"
          :options="moduleOptions"
          @change="changeModule"
        />
      </div>
      <div class="col-1">
        <h5 class="mb-0 text-center text-muted">OR</h5>
      </div>
      <div class="col-4">
        <b-button variant="primary" @click="createModule">
          Create New
        </b-button>
      </div>
    </div>
    <div v-if="moduleData">
      <b-form-group id="fieldset-1" label="Module Title" label-for="input-1">
        <b-form-input
          id="input-1"
          aria-describedby="input-live-help input-live-feedback"
          :state="moduleState.title"
          v-model="moduleData.title"
        />
        <b-form-invalid-feedback id="input-live-feedback">
          Enter at least 3 letters
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group
        id="fieldset-1"
        label="Module Description"
        label-for="input-1"
      >
        <b-form-textarea
          aria-describedby="input-live-help input-live-feedback"
          :state="moduleState.description"
          v-model="moduleData.description"
          placeholder="Enter something..."
          rows="3"
          max-rows="6"
        />
        <b-form-invalid-feedback id="input-live-feedback">
          Enter at least 3 letters
        </b-form-invalid-feedback>
      </b-form-group>
      <b-button class="mr-3" variant="success" @click="saveModule" :disabled="isLoading">
        <span v-if="!isLoading">Save Change</span>
        <b-spinner v-else small></b-spinner>
      </b-button>
      <b-button
        class="mr-3"
        v-if="!course.status && course.has_user === 0 && !isCreateAction"
        variant="danger"
        @click="deleteModule"
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
      isCreateAction: false,
      moduleData: null,
      moduleSelected: null,
      moduleOptions: [],
      moduleState: {
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
    updateCombobox(moduleData, isDelete) {
      let deletedIndex = null;

      this.moduleOptions.map((data, index) => {
        if (data.value === moduleData.id) {
          data.text = moduleData.title;

          if (isDelete) {
            deletedIndex = index;
          }
        }

        return data;
      });

      if (deletedIndex) {
        this.moduleOptions.splice(deletedIndex, 1);
      }
    },
    changeModule() {
      if (this.moduleSelected) {
        this.isCreateAction = false;
        this.$http({
          url: `/v1/master/module?entity=${this.moduleSelected}`,
          method: "GET"
        }).then(({ data }) => {
          this.moduleData = data;
        });
      } else {
        this.isCreateAction = true;
      }
    },
    createModule() {
      this.isCreateAction = true;
      this.moduleSelected = null;
      this.moduleData = {
        course_id: this.course.id,
        title: "",
        description: ""
      };
    },
    saveModule() {
      if (this.moduleData.title === "") {
        this.moduleState.title = false;
        return;
      }

      if (this.moduleData.description === "") {
        this.moduleState.description = false;
        return;
      }

      this.isLoading = true;

      if (this.isCreateAction) {
        this.$http({
          url: "/v1/master/module",
          method: "POST",
          data: this.moduleData
        }).then(
          ({
            data: {
              data: { id, title }
            }
          }) => {
            this.isLoading = false;
            this.moduleOptions.push({
              value: id,
              text: title
            });
            this.moduleData = null;
            this.moduleSelected = null;
          }
        );
      } else {
        this.$http({
          url: `/v1/master/module/update/${this.moduleSelected}`,
          method: "POST",
          data: this.moduleData
        }).then(({ data }) => {
          this.updateCombobox(this.moduleData);
          this.moduleData = null;
          this.moduleSelected = null;
          this.isLoading = false;
        });
      }
    },
    deleteModule() {
      this.$http({
        url: `/v1/advanced/module/delete/${this.moduleSelected}`,
        method: "DELETE"
      }).then(() => {
        this.updateCombobox(this.moduleData, true);
        this.moduleData = null;
        this.moduleSelected = null;
      });
    }
  }
};
</script>
