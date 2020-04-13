<template>
  <div class="row">
    <div class="col-12 mb-3">
      <div class="card card-default">
        <div class="card-body">
          <h6 class="card-title text-muted text-center">User Count</h6>
          <div class="toolbar">
            <button :class="getActiveClass('one_month')" @click="updateData('one_month')">
              1 Month
            </button>
            <button :class="getActiveClass('six_months')" @click="updateData('six_months')">
              6 Months
            </button>
            <button :class="getActiveClass('one_year')" @click="updateData('one_year')">
              1 Year
            </button>
          </div>
          <apexchart
            type="area"
            height="350"
            ref="chart"
            :options="chartOptions"
            :series="series"
          ></apexchart>
        </div>
      </div>
    </div>
    <div class="col-9">
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
    <div class="col-3">
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
  </div>
</template>
<script>
import CountTo from "vue-count-to";

let current = new Date();

export default {
  data() {
    return {
      reportCourseTotal: 0,
      reportCourseFollower: {},
      reportUserCount: {},
      series: [],
      chartOptions: {
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
          min: current.setMonth(current.getMonth() - 1),
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
        this.reportCourseTotal = data.data;
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
        this.series = data.data;
      });
    },
    getActiveClass(timeline) {
      return this.selection === timeline
        ? "btn btn-sm btn-primary"
        : "btn btn-sm btn-secondary";
    },
    updateData: function(timeline) {
      current = new Date();
      this.selection = timeline;

      switch (timeline) {
        case "one_month":
          this.$refs.chart.zoomX(
            current.setMonth(current.getMonth() - 1),
            new Date().getTime()
          );
          break;
        case "six_months":
          this.$refs.chart.zoomX(
            current.setMonth(current.getMonth() - 6),
            new Date().getTime()
          );
          break;
        case "one_year":
          this.$refs.chart.zoomX(
            current.setFullYear(current.getFullYear() - 1),
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
}
.table th,
.table td {
  padding: 0.5rem;
}
</style>
