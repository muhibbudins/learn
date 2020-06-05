<template>
  <section>
    <div class="card card-default mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-7">
            <h6 class="text-muted">About</h6>
            <h4>{{ content.title }}</h4>
            <p>{{ content.created_at }}</p>
            <div
              v-if="content.content"
              v-html="content.content"
            ></div>
          </div>
          <div class="col-5">
            <h6 class="text-muted">Table Of Contents:</h6>
            <ol v-if="content.modules">
              <li v-for="modules in content.modules" :key="modules.id">
                <h5>{{ modules.title }}</h5>
                <p>{{ modules.description }}</p>
                <div v-if="modules.lessons.length > 0">
                  <h6 class="text-muted">Lessons:</h6>
                  <ul>
                    <li v-for="lessons in modules.lessons" :key="lessons.id">
                      <p>{{ lessons.title }}</p>
                      <p>{{ lessons.description }}</p>
                    </li>
                  </ul>
                </div>
                <div v-else>This module doesn't have lessons</div>

                <div v-if="modules.quizzes.length > 0">
                  <h6 class="text-muted">Quizzes:</h6>
                  <ul>
                    <li v-for="quizzes in modules.quizzes" :key="quizzes.id">
                      <p>{{ quizzes.title }}</p>
                      <p>{{ quizzes.description }}</p>
                    </li>
                  </ul>
                </div>
                <div v-else>This module doesn't have quizzes</div>
              </li>
              <li><h5>Finish</h5></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    content: {
      type: [Object, Array],
      default: () => {}
    }
  }
};
</script>
