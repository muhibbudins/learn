<template>
  <div class="container">
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              Course Lists
            </h6>
          </div>
          <div class="col-4 ml-auto text-right">
            <b-button size="sm" variant="success" v-b-modal.modal-create>
              Create New
            </b-button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <ListCourse v-if="isLoaded" />
      </div>
    </div>
    <b-modal id="modal-create" centered title="Create Course" @ok="saveCourse">
      <div class="form-group">
        <label for="input_name">Course</label>
        <input
          type="text"
          class="form-control"
          id="input_name"
          v-model="course.title"
          aria-describedby="emailHelp"
        />
      </div>
      <div class="form-group">
        <label for="input_description">Description</label>
        <textarea
          type="description"
          class="form-control"
          id="input_description"
          v-model="course.description"
          aria-describedby="descriptionHelp"
          rows="3"
          cols="6"
        />
      </div>
    </b-modal>
  </div>
</template>
<script>
import ListCourse from "../../components/admin/ListCourse.vue";
export default {
  data() {
    return {
      course: {},
      isLoaded: false
    };
  },
  components: {
    ListCourse
  },
  mounted() {
    this.isLoaded = true;
  },
  methods: {
    saveCourse() {
      this.isLoaded = false;
      this.$http({
        url: `/v1/master/course`,
        method: "POST",
        data: this.course
      })
        .then(({ data }) => {
          this.isLoaded = true;
        })
        .catch(({ response: { data } }) => {
          if (data.messages) {
            for (const parameter in data.messages) {
              this.$notify({
                group: "alert",
                type: "warn",
                title: `Upps! Invalid parameter ${parameter}.`,
                text: data.messages[parameter].join("\n")
              });
            }
          }

          this.isLoaded = true;
        });
    }
  }
};
</script>
