<template>
  <div class="dashboard">
    <div class="page_header">
      <h4 class="header_title">Aдмин панелига хуш келибсиз</h4>
    </div>
    <div class="mb-3">
        <div class="dashboard_header">
            <div class="header_item">
                <span class="icon">
                    <i data-feather="check"></i>
                </span>
                <div class="info">
                    <p>Бажарилган топшириқлар</p>
                    <h6>22</h6>
                </div>
                <a href="#" class="read_more">Батафсил</a>
            </div>
            <div class="header_item">
                <span class="icon">
                    <i data-feather="clock"></i>
                </span>
                <div class="info">
                    <p>Aмалдаги топшириқлар</p>
                    <h6>35</h6>
                </div>
                <a href="#" class="read_more">Батафсил</a>
            </div>
            <div class="header_item">
                <span class="icon">
                    <i data-feather="slash"></i>
                </span>
                <div class="info">
                    <p>Бажарилмаган топшириқлар</p>
                    <h6>2</h6>
                </div>
                <a href="#" class="read_more">Батафсил</a>
            </div>
            <div class="item_calendar">
            <DatePicker
                class="custom-calendar max-w-full"
                :attributes="attributes"
                :first-day-of-week="2"
                locale="ru"
                :masks="{ weekdays: 'WW'}"
                format=""
                value=""
                :model-config="modelConfig"
                @update:from-page="pageChange"
            >
                <template v-slot:day-content="{ day, attributes }">
                    <div class="day_block" @click="onDayClick(day)">
                        <p>
                            <b class="day-label text-sm text-gray-900">{{ day.day }}</b>
                            <sub v-if="attributes && attributes.length">{{ attributes[0].customData.qty }}</sub>
                        </p>
                    </div>
                </template>
            </DatePicker>
            </div>
        </div>
        <div class="d_flex_jsb">
        <div id="chart" class="chart_block">
            <apexchart type="donut" :options="chartOptions" :series="series"></apexchart>
        </div>
        <div class="jv_card dashboard_table" >
            <div class="table-responsive" >
                <div class="spinner_table" v-if="loadertable">
                    <div class="spinner-border " role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <table class="table table-bordered text-center table-hover table-striped" v-if="tasksList && tasksList.length">
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
                        <tr v-for="(task,index) in tasksList">
                            <td scope="row">{{index+1}}</td>
                            <td><router-link :to='`/crm/tasks/edit/${task.id}`'> {{task.creater.surename}} {{task.creater.name}} {{task.creater.lastname}} </router-link></td>
                            <td>{{$g.getDate(task.created_at)}}г</td>
                            <td>{{$g.getDate(task.exp_date)}}г</td>
                            <td>{{task.title}}</td>
                            <td style="padding:0px;">
                                <ul>
                                    <li v-for="(item) in task.users"  :class="item.svot == 1 ? 'active' : ''">
                                        {{item.user.name}}
                                        {{item.user.surename}}
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        <!-- <div class="col-md-4 jv-card pl_30">
            <div class="list-group ">
                <router-link class="list-group-item list-group-item-action"  v-for="(task,index) in taskToday" :key="index" :to='`/crm/tasks/edit/${task.id}`' >
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1" style="width: 83%;">{{task.title.substr(0, 60)}}...</h5>
                        <small>{{ $g.getDate(task.created_at) }}г  </small>
                    </div>
                    <div class="mb-1" v-html="task.items[task.items.length-1].text.substr(0, 100)"></div>
                </router-link>
            </div>
        </div> -->
    </div>


  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import DatePicker from 'v-calendar/lib/components/date-picker.umd';
import VueApexCharts from 'vue-apexcharts'
export default {
  components: {
    DatePicker,
     apexchart: VueApexCharts,
  },
  data() {
    return {
        loaded: false,
        loadertable: false,
        days: [  ],
        currentDate: null,
        modelConfig: {
            type: 'string',
            mask: 'YYYY-MM-DD', // Uses 'iso' if missing
        },
        taskToday: [],
        tasksList: [],
        series:  [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10],
        chartOptions: {
            legend: {
                position: 'bottom',
            }
        },

    };
  },
  computed: {
    ...mapGetters("dashboard", ["getDashboard", 'getTask']),
    dates() {
      return this.days.map(day => day);
    },
    attributes() {
      return this.dates.map(date => ({
        highlight: true,
        dates: date.date,
        customData:{
            qty: date.qty,
            date: date.date
        },
        popover: {
            label: 'Tasks: '+date.qty,
            visibility: 'hover',
        }
      }));
    },
  },
  methods: {
    ...mapActions("dashboard", ["actionDashboard", 'actionTaskByDate']),
    toggleFilter() {
      this.filter.date_from = "";
      this.filter.date_to = "";
      this.filterShow = !this.filterShow;
    },
    async search() {},
    async clear() {},
    async onDayClick(day) {
        this.loadertable = true
        await this.actionTaskByDate({calendar: day.id});
        this.tasksList = this.getTask;
        this.loadertable = false
    },
    async pageChange(dataYear){
        if(this.currentDate){
            if(this.currentDate.year != dataYear.year || this.currentDate.month != dataYear.month){
                this.currentDate = dataYear
                await this.actionCalendarList(this.currentDate);
                this.monthDays =  this.getCalendarList.days;
                    if (this.monthDays) {
                    this.getCalendarList.days.forEach((item)=>{
                        if(item.status != "work"){
                            let thisTime = new Date(item.timestamp * 1000);
                            this.days.push({
                                id: item.simple,
                                date: thisTime,
                                status: 'free',
                            });
                        }
                    })
                }
            }
        }else{
            this.currentDate = dataYear
            await this.actionDashboard();
            this.getDashboard.calendar.forEach((item)=>{
                 this.days.push({
                     id: item.exp_date,
                     date: item.exp_date,
                     qty: item.qty,
                 });
            })
        }

    },
  },
  async mounted() {
    this.loaded = true;
    await this.actionTaskByDate({calendar: '2021-03-27'});
     this.taskToday = this.getTask;
  },
    updated() {
        feather.replace();
    },
};
</script>
<style scoped >
.chart_block{
    background: #FFFFFF;
    border-radius: 8px;
    padding: 24px;
    height: auto;
        width: 40%;
}
.dashboard_table{
    width: 59%
}
.dashboard_header{
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.item_calendar{
    width: calc(100% / 4 - 20px);
    overflow: hidden;
    border-radius: 8px;
    margin-top: -73px;
}

.header_item{
    background: #FFFFFF;
    border-radius: 8px;
    padding: 24px;
    height: 175px;
    width: calc(100% / 4 - 20px);
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
.header_item .icon{
    background: #2CD699;
    border-radius: 16px;
    height: 68px;
    width: 68px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 16px;
}
.header_item:nth-child(2) .icon{
    background: #0368FF;
}
.header_item:nth-child(3) .icon{
    background: #FF4930;
}
.header_item:nth-child(4) .icon{
    background: #FF4930;
}
.header_item .info{
    width: calc(100% - 85px);
}
.header_item .info p{
    font-family: "OpenSans-SemiBold";
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 19px;
    color: #475569;
    margin-bottom: 4px;
}
.header_item .info h6{
    font-family: "OpenSans-SemiBold";
    font-style: normal;
    font-weight: 500;
    font-size: 32px;
    line-height: 39px;
    color: #0B0F38;
}

.table-responsive{
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
.custom-calendar{
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
.custom-calendar.vc-container .vc-day   .weekday-7 {
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
.day_block{
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}
.day_block p{
margin-bottom: 5px;
}
.day_block b{
font-size: 14px;
}
.day_block sub{
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
.spinner_table{
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
a{
        text-decoration: none;
    color: #0c1427;
}
</style>
