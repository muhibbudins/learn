<template>
  <div>
    <div class="alert alert-danger" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <div class="row">
      <div class="col-12 col-md-6">
        <b-form-input
          v-model="keyword"
          placeholder="Search course ..."
          class="mb-3"
        />
      </div>
      <div class="col-12 col-md-6">
        <b-pagination
          v-model="courses.current_page"
          :total-rows="courses.total"
          :per-page="courses.per_page"
          aria-controls="my-table"
          class="float-right"
          @change="getCourses($event)"
        />
      </div>
    </div>
    <b-table hover :items="courses.data" :fields="fields">
      <template v-slot:cell(status)="{ item }">
        <span v-if="item.status" class="badge badge-info">Published</span>
        <span v-else class="badge badge-warning">Draft</span>
      </template>
      <template v-slot:cell(actions)="{ item }">
        <b-button
          size="sm"
          variant="outline-primary"
          @click="$router.push(`/dashboard/courses/editor/${item.id}`)"
        >
          Update
        </b-button>
      </template>
    </b-table>
  </div>
</template>

<script>
import { debounce } from '../../helpers'

export default {
  data() {
    return {
      errorMessage: false,
      fields: ["title", "status", "updated_at", "actions"],
      courses: {},
      keyword: ""
    };
  },
  mounted() {
    this.getCourses();
  },
  watch: {
    keyword: debounce(function(newKeyword) {
      this.getCourses(false, newKeyword)
    }, 500)
  },
  methods: {
    getCourses(page, keyword) {
      this.$http({
        url: `/v1/master/course?page=${page || 1}&keyword=${keyword || ''}`,
        method: "GET"
      }).then(
        ({ data }) => {
          this.courses = data;
        },
        ({ message }) => {
          this.errorMessage = message;
        }
      );
    }
  }
};
</script>
