<template>
  <div class="container">
    <div class="alert alert-danger" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="mb-0">
              User Course
            </h6>
          </div>
          <div class="col-4 ml-auto text-right">
            <b-button size="sm" variant="success" v-b-modal.modal-assign>
              Assign Course
            </b-button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body">
        <ListUserCourse v-if="isLoaded" />
      </div>
    </div>
    <b-modal
      id="modal-assign"
      centered
      title="Create Course"
      @ok="saveUserCourse"
    >
      <div class="form-group">
        <label for="input_description">Course Name</label>
        <multiselect
          v-model="userCourse.course"
          :options="courseOption"
          label="title"
          track-by="title"
          @input="renderUser"
        >
          <template slot="singleLabel" slot-scope="props">
            {{ props.option.title }} ({{ !!props.option.status ? '' : 'still in Draft' }})
          </template>
          <template slot="option" slot-scope="props">
            <span class="option__title">{{ props.option.title }}</span> - 
            <span class="option__small">{{ !!props.option.status ? '' : 'Draft' }}</span>
          </template>
        </multiselect>
      </div>
      <div v-if="isUserAvailable" class="form-group">
        <label for="input_name">Users List</label>
        <multiselect
          v-model="userCourse.users"
          :options="usersOption"
          :multiple="true"
          :close-on-select="false"
          :clear-on-select="false"
          :preserve-search="true"
          placeholder="Pick some user"
          label="name"
          track-by="name"
        />
      </div>
    </b-modal>
  </div>
</template>
<script>
import ListUserCourse from "../../components/admin/ListUserCourse.vue";

export default {
  components: {
    ListUserCourse
  },
  data() {
    return {
      isLoaded: true,
      isUserAvailable: false,
      errorMessage: null,
      userCourse: {
        users: [],
        course: null
      },
      usersOption: [],
      courseOption: []
    };
  },
  mounted() {
    this.getAllCourse();
  },
  methods: {
    renderUser() {
      this.isUserAvailable = false;
      this.getAllUser();
    },
    getAllUser() {
      this.$http({
        url: `/v1/master/student/${this.userCourse.course.id}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.usersOption = data.data.available;
          this.isUserAvailable = true;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    },
    getAllCourse() {
      this.$http({
        url: `/v1/master/course`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.courseOption = data.data;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    },
    saveUserCourse() {
      const { course, users } = this.userCourse;
      this.isLoaded = false;

      Promise.all(
        users.map(({ id }) =>
          this.$http({
            url: `/v1/master/user/course`,
            method: "POST",
            data: {
              course_id: course.id.toString(),
              user_id: id.toString()
            }
          })
        )
      ).then(() => {
        this.isLoaded = true;
        this.userCourse = {
          users: [],
          course: null
        };
      });
    }
  }
};
</script>
