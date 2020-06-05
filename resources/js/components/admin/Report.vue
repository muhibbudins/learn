<template>
  <div class="row">
    <div class="col-12 mb-3">
      <div class="card card-default">
        <div class="card-body">
          <h6 class="card-title text-muted text-center">
            Student Register Report
          </h6>
          <div class="toolbar">
            <button
              :class="getActiveClass('one_month')"
              @click="updateData('one_month')"
            >
              1 Month
            </button>
            <button
              :class="getActiveClass('six_months')"
              @click="updateData('six_months')"
            >
              6 Months
            </button>
            <button
              :class="getActiveClass('one_year')"
              @click="updateData('one_year')"
            >
              1 Year
            </button>
          </div>
          <apexchart
            type="area"
            height="350"
            ref="chart_line"
            :options="registerOptions"
            :series="registerSeries"
          />
        </div>
      </div>
    </div>
    <div class="col-6 mb-3">
      <div class="card card-default">
        <div class="card-body">
          <h6 class="card-title text-muted text-center">Course Taken</h6>
          <apexchart
            type="donut"
            height="250"
            ref="chart_donut"
            :options="totalOptions"
            :series="totalSeries"
          />
        </div>
      </div>
    </div>
    <div class="col-6 mb-3">
      <div class="card card-default">
        <div class="card-body">
          <h6 class="card-title text-muted text-center">Course Total</h6>
          <div class="text-center text-count">
            <CountTo
              :startVal="Math.random(0, 9)"
              :endVal="reportCourseTotal"
              :duration="3000"
            ></CountTo>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 mb-3">
      <div class="card card-default">
        <div class="card-body">
          <h6 class="card-title text-muted text-center">Course Follower</h6>
          <table class="table">
            <tr>
              <th>Course</th>
              <th>Followers</th>
            </tr>
            <tr v-for="course in reportCourseFollower" :key="course.course_id">
              <td>{{ course.course_title }}</td>
              <td>{{ course.followers }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CountTo from "vue-count-to";

export default {
  data() {
    const current = new Date();
    let minimumDate = null;

    if (!minimumDate) {
      minimumDate = current.setMonth(current.getMonth() - 1);
    }

    return {
      reportCourseTotal: 0,
      reportCourseFollower: {},
      reportUserCount: {},
      registerSeries: [],
      registerOptions: {
        chart: {
          type: "area",
          height: 350,
          zoom: {
            autoScaleYaxis: true
          }
        },
        stroke: {
          width: 1
        },
        dataLabels: {
          enabled: false
        },
        markers: {
          size: 0,
          style: "hollow"
        },
        xaxis: {
          type: "datetime",
          min: minimumDate,
          tickAmount: 6
        },
        tooltip: {
          x: {
            format: "dd MMM yyyy"
          }
        },
        fill: {
          type: "gradient",
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 100]
          }
        }
      },
      totalSeries: [],
      totalOptions: {
        chart: {
          type: "donut"
        },
        labels: ['Course Total', 'Course Taken'],
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200
              },
              legend: {
                position: "bottom"
              }
            }
          }
        ]
      },
      selection: "one_month"
    };
  },
  components: {
    CountTo
  },
  mounted() {
    this.loadTotalReport();
    this.loadFollowerReport();
    this.loadUserCountReport();
  },
  methods: {
    loadTotalReport(user_course, course) {
      this.$http({
        url: `/v1/report/course/total`,
        method: "GET"
      }).then(({ data }) => {
        this.reportCourseTotal = data.data.total;
        this.totalSeries = [data.data.total, data.data.taken];
      });
    },
    loadFollowerReport(user_course, course) {
      this.$http({
        url: `/v1/report/course/follower`,
        method: "GET"
      }).then(({ data }) => {
        this.reportCourseFollower = data.data;
      });
    },
    loadUserCountReport(user_course, course) {
      this.$http({
        url: `/v1/report/user`,
        method: "GET"
      }).then(({ data }) => {
        this.registerSeries = data.data;
        this.updateData("one_month");
      });
    },
    getActiveClass(timeline) {
      return this.selection === timeline
        ? "btn btn-sm btn-primary"
        : "btn btn-sm btn-secondary";
    },
    updateData: function(timeline) {
      if (!this.$refs.chart_line) {
        return false;
      }

      const currentDate = new Date();
      this.selection = timeline;

      switch (timeline) {
        case "one_month":
          this.$refs.chart_line.zoomX(
            currentDate.setMonth(currentDate.getMonth() - 1),
            new Date().getTime()
          );
          break;
        case "six_months":
          this.$refs.chart_line.zoomX(
            currentDate.setMonth(currentDate.getMonth() - 6),
            new Date().getTime()
          );
          break;
        case "one_year":
          this.$refs.chart_line.zoomX(
            currentDate.setFullYear(currentDate.getFullYear() - 1),
            new Date().getTime()
          );
          break;
        default:
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.card-default {
  min-height: 160px;
}
.text-count {
  margin-top: 20px;
  font-size: 48px;
  height: 245px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.table th,
.table td {
  padding: 0.5rem;
}
</style>
