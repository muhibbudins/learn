<template>
  <div>
    <h6>{{ content.title }}</h6>
    <p>{{ content.description }}</p>
    <hr>
    <div v-if="content.questions.length > 0">
      <h5 v-for="questions in content.questions" :key="questions.id">
        <p>{{ questions.title }}</p>
        <p>{{ questions.description }}</p>
        <small v-for="(choices, index) in questions.choices" :key="choices.id">
          <p><b>[{{ choiceOption[index] }}]</b> {{ choices.title }}</p>
        </small>
      </h5>
    </div>
    <div v-else>This module doesn't have questions</div>
  </div>
</template>

<script>
import markdown from 'marked';

export default {
  props: {
    content: {
      type: [Object, Array],
      default: () => {}
    }
  },
  data() {
    return {
      choiceOption: ['A', 'B', 'C', 'D', 'E']
    }
  },
  methods: {
    compileToHTML(text) {
      return markdown(text, { sanitize: true })
    }
  }
};
</script>
