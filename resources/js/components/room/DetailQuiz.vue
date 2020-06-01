<template>
  <section>
    <div class="card card-default mb-3">
      <div class="card-body">
        <h6>{{ content.title }}</h6>
        <p>{{ content.description }}</p>
        <div v-if="content.questions.length > 0">
          <div v-if="quizData.completed" class="alert alert-success">
            You already completed this quiz with
            <strong>total score {{ quizData.score_number }}</strong>
          </div>
          <div v-if="quizData.last_quiz" class="alert alert-warning">
            This is your final quiz, make sure you answer all questions correctly
          </div>
        </div>
        <hr />
        <div v-if="content.questions.length > 0">
          <h5 v-for="questions in content.questions" :key="questions.id">
            <p>{{ questions.title }}</p>
            <p>{{ questions.description }}</p>
            <div
              class="form-check"
              v-for="(choices, index) in questions.choices"
              :key="choices.id"
            >
              <input
                class="form-check-input"
                type="radio"
                :name="`choice-options-${questions.id}`"
                :id="`choice-options-${questions.id}-${index}`"
                :disabled="quizData.completed"
                :checked="isChecked(questions.id, choices.id)"
                @click="saveAnswer(questions.id, choices.id)"
              />
              <label
                class="form-check-label"
                :for="`choice-options-${questions.id}-${index}`"
              >
                <h6>
                  {{ choices.title }}
                  <span v-if="isChecked(questions.id, choices.id)">
                    <span
                      v-if="isCorrected(questions.id, choices.id)"
                      class="text-success"
                      >(Correct)</span
                    >
                    <span v-else class="text-danger">(Wrong)</span>
                  </span>
                </h6>
              </label>
            </div>
          </h5>
        </div>
        <div v-else>This module doesn't have questions</div>
      </div>
    </div>
    <div
      v-if="content.questions.length > 0 && !quizData.completed"
      class="card card-default"
    >
      <div class="card-body text-center">
        <button class="btn btn-outline-primary" @click="changeState" :disabled="isLoading">
          <span v-if="!isLoading">Save All Answer</span>
          <b-spinner v-else small></b-spinner>  
        </button>
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
    },
    status: {
      type: [Object, Array],
      default: () => {}
    }
  },
  data() {
    return {
      isLoading: false,
      quizData: [],
      quizChoices: {}
    };
  },
  mounted() {
    this.quizData = this.status[this.content.id];
  },
  methods: {
    isChecked(question, choice) {
      let checked = false;

      if (
        this.quizData.questions &&
        this.quizData.questions[question] &&
        this.quizData.questions[question].answer_choice === choice
      ) {
        checked = true;
      }

      return checked;
    },
    isCorrected(question, choice) {
      let checked = false;

      if (
        this.quizData.questions &&
        this.quizData.questions[question] &&
        this.quizData.questions[question].correct
      ) {
        checked = true;
      }

      return checked;
    },
    saveAnswer(question, choice) {
      this.quizChoices[question] = choice;
    },
    async changeState() {
      const {
        params: { user_course_id }
      } = this.$route;

      this.isLoading = true;

      // show popup to make sure

      Promise.all(
        Object.keys(this.quizChoices).map(question => {
          return this.$http({
            url: `/v1/room/save/question`,
            method: "POST",
            data: {
              user_course_id,
              module_quiz_question_id: question,
              module_quiz_choice_id: this.quizChoices[question].toString()
            }
          });
        })
      ).then(() => {
        this.isLoading = false;
        // do something with data here
        window.location.reload();
      });
    }
  }
};
</script>
