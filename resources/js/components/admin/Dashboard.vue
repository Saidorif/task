<template>
  <div class="dashboard">
    <div class="page_header">
      <h4 class="header_title">Aдмин панелига хуш келибсиз</h4>
    </div>
    <div class="mb-5">
      <div class="dashboard_header">
        <div class="header_item">
          <span class="icon">
            <i data-feather="layers"></i>
          </span>
          <div class="info">
            <p>Жами</p>
            <h6 v-if="getDashboard.info">
              {{ getDashboard.info[0].total ? getDashboard.info[0].total : 0 }}
            </h6>
          </div>
          <router-link class="read_more" to="/crm/tasks">Батафсил</router-link>
        </div>
        <div class="header_item">
          <span class="icon">
            <i data-feather="check"></i>
          </span>
          <div class="info">
            <p>Бажарилган топшириқлар</p>
            <h6 v-if="getDashboard.info">
              {{
                getDashboard.info[0].accepted
                  ? getDashboard.info[0].accepted
                  : 0
              }}
            </h6>
          </div>
          <router-link class="read_more" to="/crm/tasks?status=accepted"
            >Батафсил</router-link
          >
        </div>
        <div class="header_item">
          <span class="icon">
            <i data-feather="clock"></i>
          </span>
          <div class="info">
            <p>Aмалдаги топшириқлар</p>
            <h6 v-if="getDashboard.info">
              {{
                getDashboard.info[0].active ? getDashboard.info[0].active : 0
              }}
            </h6>
          </div>
          <router-link class="read_more" to="/crm/tasks?status=active"
            >Батафсил</router-link
          >
        </div>
        <div class="header_item">
          <span class="icon">
            <i data-feather="slash"></i>
          </span>
          <div class="info">
            <p>Муддати ўтган</p>
            <h6 v-if="getDashboard.info">
              {{
                getDashboard.info[0].rejected
                  ? getDashboard.info[0].rejected
                  : 0
              }}
            </h6>
          </div>
          <router-link class="read_more" to="/crm/tasks?status=rejected"
            >Батафсил</router-link
          >
        </div>
        <div class="header_item">
          <span class="icon">
            <i data-feather="archive"></i>
          </span>
          <div class="info">
            <p>Мухим топшириқлар</p>
            <h6 v-if="getDashboard.info">
              {{
                getDashboard.info[0].importants
                  ? getDashboard.info[0].importants
                  : 0
              }}
            </h6>
          </div>
          <router-link class="read_more" to="/crm/tasks?status=is_important"
            >Батафсил</router-link
          >
        </div>
      </div>
      <div class="row">
        <div class="col-md-9">
            <!-- <div class="dashboard_bar_chart" id="chart" v-if="!loaded">
                <apexchart
                type="bar"
                :options="chartOptions"
                :series="series"
                ></apexchart>
            </div> -->
                  <div class="jv_card">
        <h6>Ижро интизомининг бузилиш мониторинги</h6>
        <table
          class="table table-bordered text-center table-hover table-striped"
          v-if="getDashboard.kartoteka && getDashboard.kartoteka.length"
        >
          <thead>
            <tr>
              <th scope="col" style="width: 40px">№</th>
              <th scope="col">Жўнатувчи</th>
              <th scope="col">Берилган сана</th>
              <th scope="col">Ижро муддати</th>
              <th scope="col">Қисқа мазмуни</th>
              <th scope="col">Бажарувчилар</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(task, index) in getDashboard.kartoteka">
              <td scope="row">{{ index + 1 }}</td>
              <td>
                <router-link :to="`/crm/tasks/edit/${task.id}`">
                  {{ task.creater.surename }} {{ task.creater.name }}
                  {{ task.creater.lastname }}
                </router-link>

              </td>
              <td>{{ $g.getDate(task.created_at) }}г</td>
              <td>{{ $g.getDate(task.exp_date) }}г</td>
              <td>{{ task.title }}</td>
              <td style="padding: 0px">
                <ul>
                  <li
                    v-for="item in task.users"
                    :class="item.svot == 1 ? 'active' : ''"
                    v-tooltip.top-center=" '<b>' + item.user.position.name + '</b><br> ' + item.user.name + ' ' + item.user.surename"
                    >
                    {{ item.user.position.structure.name }}
                  </li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
        </div>
        <div class="col-md-3">
            <DatePicker
                class="custom-calendar max-w-full"
                :attributes="attributes"
                :first-day-of-week="2"
                locale="ru"
                :masks="{ weekdays: 'WW' }"
                format=""
                value=""
                :model-config="modelConfig"
                @update:from-page="pageChange"
            >
                <template v-slot:day-content="{ day, attributes }">
                <div class="day_block" @click="onDayClick(day)">
                    <p>
                    <b class="day-label text-sm text-gray-900">{{ day.day }}</b>
                    <sub v-if="attributes && attributes.length">{{
                        attributes[0].customData.qty
                    }}</sub>
                    </p>
                </div>
                </template>
            </DatePicker>
        </div>
      </div>



    </div>
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" style="max-width: 80%">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Топшириқлар рўйхати
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table
                class="table table-bordered text-center table-hover table-striped"
                v-if="tasksList && tasksList.length"
              >
                <thead>
                  <tr>
                    <th scope="col">№</th>
                    <th scope="col">Жўнатувчи</th>
                    <th scope="col">Берилган сана</th>
                    <th scope="col">Ижро муддати</th>
                    <th scope="col">Қисқа мазмуни</th>
                    <th scope="col">Бажарувчилар</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(task, index) in tasksList">
                    <td scope="row">{{ index + 1 }}</td>
                    <td>
                      <router-link :to="`/crm/tasks/edit/${task.id}`">
                        {{ task.creater.surename }} {{ task.creater.name }}
                        {{ task.creater.lastname }}
                      </router-link>
                    </td>
                    <td>{{ $g.getDate(task.created_at) }}г</td>
                    <td>{{ $g.getDate(task.exp_date) }}г</td>
                    <td>{{ task.title }}</td>
                    <td style="padding: 0px">
                      <ul>
                        <li
                          v-for="item in task.users"
                          :class="item.svot == 1 ? 'active' : ''"
                        >
                          {{ item.user.name }}
                          {{ item.user.surename }}
                        </li>
                      </ul>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import DatePicker from "v-calendar/lib/components/date-picker.umd";
import VueApexCharts from "vue-apexcharts";
export default {
  components: {
    DatePicker,
    apexchart: VueApexCharts,
  },
  data() {
    return {
      loaded: true,
      modalcalenar: null,
      days: [],
      currentDate: null,
      modelConfig: {
        type: "string",
        mask: "YYYY-MM-DD", // Uses 'iso' if missing
      },
      taskToday: [],
      tasksList: [],
      series: [
        {
          name: "Умумий топшириқлар сони",
          data: [],
        },
      ],
      chartOptions: {
        chart: {
          height: 'auto',
          width: "100%",
          type: "bar",
          events: {
            click: function (chart, w, e) {
              //   console.log( w)
            },
          },
        },
        plotOptions: {
          bar: {
            columnWidth: "45%",
            distributed: true,
            horizontal: true,
          },
        },
        dataLabels: {
          enabled: false,
          position: 'top'
        },
        legend: {
            show: false,
            horizontalAlign: 'left',
            markers: {
                width: 12,
                height: 12,
                strokeWidth: 0,
                strokeColor: '#fff',
                fillColors: [
                    "#000",
                    "#FDF5BF",
                    "#FFD5FF",
                    "#92D1C3",
                    "#8BB8A8",
                    "#FAC8CD",
                    "#D7BCC8",
                    "#98B6B1",
                    "#629677",
                    "#1B1725",
                    "#495D63",
                    "#2B4141",
                    "#0EB1D2",
                    "#34E4EA",
                    "#8AB9B5",
                    "#C8C2AE",
                    "#534B62",
                    "#A499B3",
                    "#D0BCD5",
                    "#226CE0",
                    "#226CE0",
                    "#6F1D1B",
                    "#BB9457",
                    "#432818",
                    "#99582A",
                    "#FFE6A7",
                    "#CEBEBE",
                    "#ECE2D0",
                    "#D5B9B2",
                    "#A26769",
                    "#6D2E46",
                    "#F6F7EB",
                    "#E94F37",
                    "#393E41",
                    "#3F88C5",
                    "#44BBA4",
                    "#DDD78D",
                    "#DCBF85",
                    "#8B635C",
                    "#60594D",
                    "#93A29B",
                    "#DEF2C8",
                    "#C5DAC1",
                    "#BCD0C7",
                    "#A9B2AC",
                    "#898980",
                    "#2274A5",
                    "#E7EB90",
                    "#FADF63",
                    "#E6AF2E",
                ],
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0
            },
        },

        fill: {
          colors: [
            "#000",
            "#FDF5BF",
            "#FFD5FF",
            "#92D1C3",
            "#8BB8A8",
            "#FAC8CD",
            "#D7BCC8",
            "#98B6B1",
            "#629677",
            "#1B1725",
            "#495D63",
            "#2B4141",
            "#0EB1D2",
            "#34E4EA",
            "#8AB9B5",
            "#C8C2AE",
            "#534B62",
            "#A499B3",
            "#D0BCD5",
            "#226CE0",
            "#226CE0",
            "#6F1D1B",
            "#BB9457",
            "#432818",
            "#99582A",
            "#FFE6A7",
            "#CEBEBE",
            "#ECE2D0",
            "#D5B9B2",
            "#A26769",
            "#6D2E46",
            "#F6F7EB",
            "#E94F37",
            "#393E41",
            "#3F88C5",
            "#44BBA4",
            "#DDD78D",
            "#DCBF85",
            "#8B635C",
            "#60594D",
            "#93A29B",
            "#DEF2C8",
            "#C5DAC1",
            "#BCD0C7",
            "#A9B2AC",
            "#898980",
            "#2274A5",
            "#E7EB90",
            "#FADF63",
            "#E6AF2E",
          ],
        },
        xaxis: {
          categories: [],
          labels: {
            show: false,
            reversed:true,
            position: 'top',
            style: {
              colors: ["#000"],
              fontSize: "12",
              fontWeight:600,
              cssClass:'java'
            },
          },
        },
        yaxis: {
          labels: {
            show: true,
                        style: {
              colors: ["#000"],
              fontSize: "14px",
              lineHeight:"20px"
            },
          },

        },
      },
    };
  },
  computed: {
    ...mapGetters("dashboard", ["getDashboard", "getTask"]),
    dates() {
      return this.days.map((day) => day);
    },
    attributes() {
      return this.dates.map((date) => ({
        highlight: true,
        dates: date.date,
        customData: {
          qty: date.qty,
          date: date.date,
        },
        popover: {
          label: "Tasks: " + date.qty,
          visibility: "hover",
        },
      }));
    },
  },
  methods: {
    ...mapActions("dashboard", ["actionDashboard", "actionTaskByDate"]),
    toggleFilter() {
      this.filter.date_from = "";
      this.filter.date_to = "";
      this.filterShow = !this.filterShow;
    },
    async search() {},
    async clear() {},
    async onDayClick(day) {
      await this.actionTaskByDate({ calendar: day.id });
      if (this.getTask.result && this.getTask.result.length) {
        this.modalcalenar.show();
        this.tasksList = this.getTask.result;
      }
    },
    async pageChange(dataYear) {
      if (this.currentDate) {
        if (
          this.currentDate.year != dataYear.year ||
          this.currentDate.month != dataYear.month
        ) {
          this.currentDate = dataYear;
          await this.actionCalendarList(this.currentDate);
          this.monthDays = this.getCalendarList.days;
          if (this.monthDays) {
            this.getCalendarList.days.forEach((item) => {
              if (item.status != "work") {
                let thisTime = new Date(item.timestamp * 1000);
                this.days.push({
                  id: item.simple,
                  date: thisTime,
                  status: "free",
                });
              }
            });
          }
        }
      } else {
        this.currentDate = dataYear;
        await this.actionDashboard();
        this.getDashboard.calendar.forEach((item) => {
          this.days.push({
            id: item.exp_date,
            date: item.exp_date,
            qty: item.qty,
          });
        });
      }
    },
  },
  async mounted() {
    await this.actionTaskByDate({ calendar: "2021-03-27" });
    if (this.getDashboard.chart) {
      this.getDashboard.chart.forEach((item) => {
        let total = Math.floor(Math.random() * (100 - 1 + 1) + 1);
        this.series[0].data.push(item.total);
        this.chartOptions.xaxis.categories.push(item.name);
      });
    }
    this.taskToday = this.getTask;
    feather.replace();
    this.loaded = false;
    this.modalcalenar = new bootstrap.Modal(
      document.getElementById("exampleModal"),
      {
        keyboard: false,
      }
    );
  },
  updated() {
    feather.replace();
  },
};
</script>
<style scoped >
.chart_block {
  background: #ffffff;
  border-radius: 8px;
  padding: 24px;
  height: auto;
  width: 40%;
}
.dashboard_table {
  width: 59%;
}
.dashboard_header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.item_calendar {
  width: calc(100% / 4 - 20px);
  overflow: hidden;
  border-radius: 8px;
  margin-top: -73px;
}

.header_item {
  background: #ffffff;
  border-radius: 8px;
  padding: 24px;
  height: 175px;
  width: calc(100% / 5 - 15px);
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}
.header_item .icon {
  background: #2cd699;
  border-radius: 16px;
  height: 68px;
  width: 68px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  margin-right: 16px;
}
.header_item:nth-child(2) .icon {
  background: #0368ff;
}
.header_item:nth-child(3) .icon {
  background: #ff4930;
}
.header_item:nth-child(4) .icon {
  background: #0c1427;
}
.header_item .info {
  width: calc(100% - 85px);
}
.header_item .info p {
  font-family: "OpenSans-SemiBold";
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 19px;
  color: #475569;
  margin-bottom: 4px;
}
.header_item .info h6 {
  font-family: "OpenSans-SemiBold";
  font-style: normal;
  font-weight: 500;
  font-size: 32px;
  line-height: 39px;
  color: #0b0f38;
}

.table-responsive {
  transition: 1s;
}
.mw_5000 {
  min-width: 5000px;
}
.overauto {
  overflow: auto;
}
::-webkit-scrollbar {
  width: 0px;
}
::-webkit-scrollbar-track {
  display: none;
}
.custom-calendar {
  border: none;
}
.custom-calendar.vc-container {
  --day-border: 1px solid #e0ecf8;
  --day-border-highlight: 1px solid #e0ecf8;
  --day-width: 90px;
  --day-height: 90px;
  --weekday-bg: #e0ecf8;
  --weekday-border: 1px solid #e0ecf8;
  border-radius: 0;
  width: 100%;
}
.custom-calendar.vc-container .vc-header {
  background-color: #f1f5f8;
  padding: 10px 0;
}

.custom-calendar.vc-container .vc-weekday {
  background-color: var(--weekday-bg);
  border-bottom: var(--weekday-border);
  border-top: var(--weekday-border);
  padding: 5px 0;
}
.custom-calendar.vc-container .vc-day {
  padding: 0 5px 3px 5px;
  text-align: left;
  height: var(--day-height);
  min-width: var(--day-width);
  background-color: white;
}
.custom-calendar.vc-container .vc-day .weekday-1,
.custom-calendar.vc-container .vc-day .weekday-7 {
  background-color: #eff8ff;
}
.custom-calendar.vc-container:not(.on-bottom) {
  border-bottom: var(--day-border);
}
.custom-calendar.vc-container:not(.on-bottom) .weekday-1 {
  border-bottom: var(--day-border-highlight);
}
.custom-calendar.vc-container .vc-day-dots {
  margin-bottom: 5px;
}
.custom-calendar.vc-container:not(.on-right) {
  border-right: var(--day-border);
}
.day_block {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}
.day_block p {
  margin-bottom: 5px;
}
.day_block b {
  font-size: 14px;
}
.day_block sub {
  border: 1px solid #f27474;
  border-radius: 4px;
  height: 16px;
  width: 16px;
  display: flex;
  background: #dc3545;
  color: white;
  font-weight: bold;
  line-height: 0;
  letter-spacing: 0px;
  font-size: 10px;
  align-items: center;
  position: absolute;
  right: 0px;
  top: 0px;
  justify-content: center;
}
.spinner_table {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
}
a {
  text-decoration: none;
  color: #0c1427;
}
</style>
