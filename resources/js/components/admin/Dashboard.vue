<template>
  <div class="dashboard">
    <div class="page_header">
      <h4 class="header_title">Добро пожаловать в админ панель</h4>
    </div>
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
    <div class="jv_card">
        <div class="table-responsive" >
            <div class="spinner_table" v-if="loadertable">
                <div class="spinner-border " role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
			<table class="table table-bordered text-center table-hover table-striped" v-if="getTask && getTask.length">
				<thead>
					<tr>
						<th scope="col">№</th>
						<th scope="col">Jo'natuvchi</th>
						<th scope="col">Berilgan sana</th>
						<th scope="col">Qisqa mazmuni</th>
						<th scope="col">Bajaruvchilar</th>
						<th scope="col">Ijro muddati</th>
					</tr>
				</thead>
				<tbody>
					 <tr v-for="(task,index) in getTask">
						<td scope="row">{{index+1}}</td>
						<td>{{task.creater.surename}} {{task.creater.name}} {{task.creater.lastname}}</td>
						<td>{{$g.getDate(task.created_at)}}г</td>
						<td>{{task.title}}</td>
						<td style="padding:0px;">
                            <ul>
                                <li v-for="(item) in task.users"  :class="item.svot == 1 ? 'active' : ''">
                                    {{item.user.name}}
                                    {{item.user.surename}}
                                </li>
                            </ul>
                        </td>
						<td>{{$g.getDate(task.exp_date)}}г</td>
					</tr>
				</tbody>
			</table>

		</div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import DatePicker from 'v-calendar/lib/components/date-picker.umd'
export default {
  components: {
      DatePicker
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
    await this.actionDashboard();
  },
};
</script>
<style scoped >
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
.custom-calendar.vc-container {
  --day-border: 1px solid #b8c2cc;
  --day-border-highlight: 1px solid #b8c2cc;
  --day-width: 90px;
  --day-height: 90px;
  --weekday-bg: #f8fafc;
  --weekday-border: 1px solid #eaeaea;
  border-radius: 0;
  width: 100%;
}
.custom-calendar.vc-container .vc-header {
    background-color: #f1f5f8;
    padding: 10px 0;
}
.custom-calendar.vc-container .vc-weeks {
    padding: 0;
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
.day_block b{
    font-size: 24px;
}
.day_block sub{
    border: 1px solid red;
    border-radius: 0px;
    height: 24px;
    width: 30px;
    display: flex;
    background: red;
    color: white;
    font-weight: bold;
    line-height: 0;
    letter-spacing: 0px;
    font-size: 14px;
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
</style>
