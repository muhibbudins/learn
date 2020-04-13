<template>
  <div>
    <h4>{{ content.title }}</h4>
    <p>{{ content.description }}</p>
    <div v-if="content.content" v-html="compileToHTML(content.content)"></div>
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
  methods: {
    compileToHTML(text) {
      return markdown(text, { sanitize: true })
    }
  }
};
</script>
