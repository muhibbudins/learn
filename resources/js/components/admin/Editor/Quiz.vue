<template>
  <section ref="container">
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
          v-model="quizSelected"
          :options="quizOptions"
          @change="changeQuiz"
        />
      </div>
      <div class="col-1">
        <h5 class="mb-0 text-center text-muted">OR</h5>
      </div>
      <div class="col-3">
        <b-button variant="primary" @click="createQuiz">
          Create New
        </b-button>
      </div>
    </div>
    <div v-if="quizData">
      <h6 class="text-muted mt-3">Quiz Detail :</h6>
      <b-form-group
        label="Title"
        aria-describedby="input-live-help input-live-feedback"
        :state="quizState.title"
        :label-for="`input-${quizData.id}`"
      >
        <b-form-input :id="`input-${quizData.id}`" v-model="quizData.title" />
      </b-form-group>
      <b-form-invalid-feedback id="input-live-feedback">
        Enter at least 3 letters
      </b-form-invalid-feedback>
      <b-form-group
        label="Description"
        aria-describedby="input-live-help input-live-feedback"
        :state="quizState.description"
      >
        <b-form-textarea
          v-model="quizData.description"
          placeholder="Enter something..."
          rows="3"
          max-rows="6"
        />
      </b-form-group>
      <b-form-invalid-feedback id="input-live-feedback">
        Enter at least 3 letters
      </b-form-invalid-feedback>
      <div class="mb-3">
        <b-button class="mr-3" variant="success" @click="saveQuiz">
          Save Quiz
        </b-button>
        <b-button
          v-if="
            !course.status && !isCreateAction && quizData.questions.length === 0
          "
          class="mr-3"
          variant="danger"
          @click="deleteQuiz"
        >
          Delete
        </b-button>
        <b-button @click="$router.back()">
          Cancel
        </b-button>
      </div>
      <h6 class="text-muted">Quiz Questions :</h6>
      <b-card
        v-for="(question, questionIndex) in quizData.questions"
        :key="question.id"
        class="mb-2"
      >
        <div class="row align-items-center">
          <div class="col-1">
            <h4 class="mb-0 text-center text-muted">
              {{ questionIndex + 1 }}
            </h4>
          </div>
          <div class="col-7">
            <b-form-group
              :label-for="`input-question-${questionIndex}`"
              class="mb-0"
            >
              <b-form-input
                :id="`input-question-${questionIndex}`"
                v-model="question.title"
              />
            </b-form-group>
          </div>
          <div class="col-4">
            <b-button
              v-b-toggle="`collapse-${questionIndex}-choices`"
              size="sm"
              variant="primary"
            >
              Open Choices
            </b-button>
            <b-button
              size="sm"
              variant="success"
              @click="saveQuestion(quizData.id, questionIndex)"
            >
              Save Question
            </b-button>
            <b-button
              v-if="!course.status && question.choices.length === 0"
              size="sm"
              variant="danger"
              @click="deleteQuestion(questionIndex)"
            >
              Delete
            </b-button>
          </div>
        </div>
        <b-collapse
          :id="`collapse-${questionIndex}-choices`"
          accordion="choices-accordion"
          class="mt-3 text-center"
        >
          <b-card>
            <div
              class="row align-items-center mb-3"
              v-for="(choice, choiceIndex) in question.choices"
              :key="choice.id"
            >
              <div class="col-1">
                <h4 class="mb-0 text-center text-muted">
                  {{ numericChoices[choiceIndex] }}
                </h4>
              </div>
              <div class="col-5">
                <b-form-group
                  :label-for="`input-choice-${questionIndex}-${choiceIndex}`"
                  class="mb-0"
                >
                  <b-form-input
                    :id="`input-choice-${questionIndex}-${choiceIndex}`"
                    v-model="choice.title"
                  />
                </b-form-group>
              </div>
              <div class="col-3">
                <b-form-radio
                  v-model="question.answer"
                  :value="choice.id"
                  class="mb-2 mr-sm-2 mb-sm-0"
                  :name="`input-answer-question-${questionIndex}`"
                >
                  Correct Answer
                </b-form-radio>
              </div>
              <div class="col-3">
                <b-button
                  size="sm"
                  variant="success"
                  @click="saveChoices(question.id, questionIndex, choiceIndex)"
                >
                  Save Answer
                </b-button>
                <b-button
                  v-if="!course.status"
                  size="sm"
                  variant="danger"
                  @click="deleteChoices(questionIndex, choiceIndex)"
                >
                  Delete
                </b-button>
              </div>
            </div>
            <b-button
              v-if="question.choices && question.choices.length < 5"
              size="sm"
              variant="outline-primary"
              @click="addNewAnswer($event, questionIndex)"
            >
              Add New Answer
            </b-button>
          </b-card>
        </b-collapse>
      </b-card>
      <div class="text-center mt-3">
        <b-button
          v-if="quizData.questions && quizData.questions.length < 10"
          size="sm"
          variant="outline-primary"
          @click="addNewQuestion($event)"
        >
          Add New Question
        </b-button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    course: {
      type: [Object, Array],
      default: () => {}
    }
  },
  data() {
    return {
      numericChoices: ["A", "B", "C", "D", "E"],
      isLoadedState: true,
      isCreateAction: false,
      moduleSelected: null,
      moduleOptions: [],
      quizData: null,
      quizSelected: null,
      quizOptions: [
        {
          value: null,
          text: "- Quiz is Empty -"
        }
      ],
      quizState: {
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
    updateCombobox(QuizData, isDelete) {
      let deletedIndex = null;

      this.quizOptions.map((data, index) => {
        if (data.value === QuizData.id) {
          data.text = QuizData.title;

          if (isDelete) {
            deletedIndex = index;
          }
        }

        return data;
      });

      if (deletedIndex) {
        this.quizOptions.splice(deletedIndex, 1);
      }
    },
    changeModule() {
      this.quizData = null;

      if (this.moduleSelected) {
        if (this.course.quizzes) {
          this.quizOptions = [
            { value: null, text: "Select Quiz" },
            ...this.course.quizzes[this.moduleSelected]
          ];
        }
      }
    },
    changeQuiz() {
      if (this.quizSelected) {
        this.isCreateAction = false;
        this.$http({
          url: `/v1/master/module/quiz?entity=${this.quizSelected}`,
          method: "GET"
        }).then(({ data }) => {
          this.quizData = data;
        });
      } else {
        this.isCreateAction = true;
      }
    },
    easeInCubic(t) {
      return t * t * t;
    },
    scrollToElem(
      startTime,
      currentTime,
      duration,
      scrollEndElemTop,
      startScrollOffset
    ) {
      const runtime = currentTime - startTime;
      let progress = runtime / duration;

      progress = Math.min(progress, 1);

      const ease = this.easeInCubic(progress);

      window.scroll(0, startScrollOffset + scrollEndElemTop * ease);
      if (runtime < duration) {
        requestAnimationFrame(timestamp => {
          const currentTime = timestamp || new Date().getTime();
          this.scrollToElem(
            startTime,
            currentTime,
            duration,
            scrollEndElemTop,
            startScrollOffset
          );
        });
      }
    },
    scrollToBottom(button) {
      return setTimeout(() => {
        return requestAnimationFrame(timestamp => {
          const stamp = timestamp || new Date().getTime();
          const duration = 1200;
          const start = stamp;

          const startScrollOffset = window.pageYOffset;
          const scrollEndElemTop = button.getBoundingClientRect().top - 100;

          this.scrollToElem(
            start,
            stamp,
            duration,
            scrollEndElemTop,
            startScrollOffset
          );
        });
      }, 100);
    },
    addNewAnswer(e, questionIndex) {
      this.quizData.questions[questionIndex].choices.push({
        title: "",
        module_quiz_question_id: this.quizData.questions[questionIndex].id,
        answer: 0
      });
      this.scrollToBottom(e.target);
    },
    addNewQuestion(e) {
      this.quizData.questions.push({
        title: "",
        answer: 0,
        choices: []
      });
      this.scrollToBottom(e.target);
    },
    createQuiz() {
      if (!this.moduleSelected) {
        return;
      }

      this.isLoadedState = false;
      this.isCreateAction = true;
      this.quizSelected = null;
      this.quizData = {
        module_id: this.moduleSelected,
        title: "",
        description: "",
        content: "",
        questions: []
      };
    },
    saveQuiz() {
      if (!this.moduleSelected) {
        return;
      }

      if (this.quizData.title === "") {
        this.quizState.title = false;
        return;
      }

      if (this.quizData.description === "") {
        this.quizState.description = false;
        return;
      }

      if (this.isCreateAction) {
        this.$http({
          url: "/v1/master/module/quiz",
          method: "POST",
          data: this.quizData
        }).then(({ data: { data } }) => {
          this.quizOptions.push({
            value: data.id,
            text: data.title
          });
          this.quizSelected = data.id;
          this.quizData = data;
        });
      } else {
        this.$http({
          url: `/v1/master/module/quiz/update/${this.quizSelected}`,
          method: "POST",
          data: this.quizData
        }).then(({ data: { data } }) => {
          this.updateCombobox(this.quizData);
          this.quizSelected = data.id;
          this.quizData = data;
        });
      }
    },
    saveQuestion(parentId, currentIndex) {
      if (!parentId) {
        return;
      }

      const questionData = this.quizData.questions[currentIndex];

      // if (this.quizData.title === "") {
      //   this.quizState.title = false;
      //   return;
      // }

      questionData.module_quiz_id = parentId;

      if (!questionData.id) {
        this.$http({
          url: "/v1/master/module/quiz/question",
          method: "POST",
          data: questionData
        }).then(({ data: { data } }) => {
          this.quizData.questions[currentIndex] = data;
        });
      } else {
        this.$http({
          url: `/v1/master/module/quiz/question/update/${questionData.id}`,
          method: "POST",
          data: questionData
        }).then(({ data: { data } }) => {
          this.quizData.questions[currentIndex] = data;
        });
      }
    },
    saveChoices(parentId, parentIndex, currentIndex) {
      if (!parentId) {
        return;
      }

      const questionData = this.quizData.questions[parentIndex];
      const choiceData = questionData.choices[currentIndex];

      // if (this.quizData.title === "") {
      //   this.quizState.title = false;
      //   return;
      // }

      if (!choiceData.id) {
        this.$http({
          url: "/v1/master/module/quiz/choice",
          method: "POST",
          data: choiceData
        }).then(({ data: { data } }) => {
          this.quizData.questions[parentIndex].choices[currentIndex] = data;
        });
      } else {
        if (questionData.answer === choiceData.id) {
          choiceData.answer = "1";
        } else {
          choiceData.answer = "0";
        }
        this.$http({
          url: `/v1/master/module/quiz/choice/update/${choiceData.id}`,
          method: "POST",
          data: choiceData
        }).then(({ data: { data } }) => {
          this.quizData.questions[parentIndex].choices[currentIndex] = data;
        });
      }
    },
    deleteQuiz() {
      this.$http({
        url: `/v1/advanced/module/quiz/delete/${this.quizSelected}`,
        method: "DELETE"
      }).then(() => {
        this.updateCombobox(this.quizData, true);
        this.quizData = null;
        this.quizSelected = null;
      });
    },
    deleteQuestion(questionIndex) {
      const questionData = this.quizData.questions[questionIndex];

      if (questionData.id) {
        this.$http({
          url: `/v1/advanced/module/quiz/question/delete/${questionData.id}`,
          method: "DELETE"
        });
      }

      this.quizData.questions.splice(questionIndex, 1);
    },
    deleteChoices(questionIndex, choiceIndex) {
      const questionData = this.quizData.questions[questionIndex];
      const choiceData = questionData.choices[choiceIndex];

      if (choiceData.id) {
        this.$http({
          url: `/v1/advanced/module/quiz/choice/delete/${choiceData.id}`,
          method: "DELETE"
        });
      }

      this.quizData.questions[questionIndex].choices.splice(choiceIndex, 1);
    }
  }
};
</script>
