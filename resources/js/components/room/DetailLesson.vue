<template>
  <section>
    <div class="card card-default mb-3">
      <div class="card-body">
        <h4>{{ content.title }}</h4>
        <p>{{ content.description }}</p>
        <div
          v-if="content.content"
          v-html="compileToHTML(content.content)"
        ></div>
      </div>
    </div>
    <div class="card card-default">
      <div class="card-body text-center">
        <button
          :class="{
            btn: true,
            'btn-primary': lessonData.completed,
            'btn-outline-primary': !lessonData.completed
          }"
          @click="changeState"
        >
          <span v-if="lessonData.completed">
            Mark as Uncompleted
          </span>
          <span v-else>
            Mark as Completed
          </span>
        </button>
      </div>
    </div>
  </section>
</template>

<script>
import markdown from "marked";

export default {
  props: {
    content: {
      type: [Object, Array],
      default: () => {}
    },
    status: {
      type: [Object, Array],
      default: () => {}
    }
  },
  data() {
    return {
      lessonData: {}
    };
  },
  mounted() {
    this.lessonData = this.status[this.content.id];
  },
  methods: {
    compileToHTML(text) {
      return markdown(text, { sanitize: true });
    },
    async changeState() {
      const {
        params: { user_course_id, content_id }
      } = this.$route;
      const isCompleted = this.lessonData.completed ? 0 : 1;

      const { data } = await this.$http({
        url: `/v1/room/save/module`,
        method: "POST",
        data: {
          user_course_id,
          module_lesson_id: content_id,
          completed: isCompleted
        }
      });

      this.lessonData.completed = isCompleted;
    }
  }
};
</script>
