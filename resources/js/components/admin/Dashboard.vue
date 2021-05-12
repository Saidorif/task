<template>
  <div class="dashboard">
    <div class="page_header">
      <h4 class="header_title">Aдмин панелига хуш келибсиз</h4>
    </div>
    <div class="mb-5">
        <div class="dashboard_header_top">
            <div class="category_item active" @click="selectCategory('Умумий масалалар')">Умумий масалалар</div>
            <div class="category_item" @click="selectCategory('Автомобил транспорти')">Автомобил транспорти</div>
            <div class="category_item" @click="selectCategory('Темир йўл транспорти')">Темир йўл транспорти</div>
            <div class="category_item" @click="selectCategory('Хаво транспорти')">Хаво транспорти</div>
            <div class="category_item" @click="selectCategory('Халқаро хамкорлик')">Халқаро хамкорлик</div>
            <div class="category_item" @click="selectCategory('Худудлар')">Худудлар</div>
            <div class="category_item" @click="selectCategory('Йўл хўжалиги')">Йўл хўжалиги</div>
        </div>
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
            <div class="header_item mr_0">
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
                            v-tooltip.top-center="
                            '<b>' +
                            item.user.position.name +
                            '</b><br> ' +
                            item.user.name +
                            ' ' +
                            item.user.surename
                            "
                        >
                            {{ item.user.position.structure.name }}
                        </li>
                        </ul>
                    </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <div class="calendar_dash">
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
                    <td @click="hideModal()">
                      <router-link :to="getUser.id == task.creater.id ? `/crm/tasks/edit/${task.id}` : `/crm/user-task/edit/${task.id}`">
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
          height: "auto",
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
          position: "top",
        },
        legend: {
          show: false,
          horizontalAlign: "left",
          markers: {
            width: 12,
            height: 12,
            strokeWidth: 0,
            strokeColor: "#fff",
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
            offsetY: 0,
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
            reversed: true,
            position: "top",
            style: {
              colors: ["#000"],
              fontSize: "12",
              fontWeight: 600,
              cssClass: "java",
            },
          },
        },
        yaxis: {
          labels: {
            show: true,
            style: {
              colors: ["#000"],
              fontSize: "14px",
              lineHeight: "20px",
            },
          },
        },
      },
    };
  },
  computed: {
    ...mapGetters("dashboard", ["getDashboard", "getTask"]),
    ...mapGetters(['getUser']),
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
    async selectCategory(val){
        await this.actionDashboard(val);
    },
    hideModal(){
        this.modalcalenar.hide();
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
<style scoped>
    .dashboard_header_top{
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding: 0px 10px;
    }
    .dashboard_header_top .category_item{
        background: #fff;
        border-radius: 8px;
        padding: 10px 30px;
        cursor: pointer;
        font-weight: bold;
        transition: .5s;
    }
    .dashboard_header_top .category_item.active,
    .dashboard_header_top .category_item:hover{
        box-shadow: 0px 0px 6px 0px #b1e3ff;
        color: #2f6cc7;
    }
</style>
