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
            @dayclick="onDayClick"
            format=""
            value=""
            :model-config="modelConfig"
        ></DatePicker>
    <div class="jv_card">

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
                days: [  ],
        modelConfig: {
            type: 'string',
            mask: 'YYYY-MM-DD', // Uses 'iso' if missing
        },
    };
  },
  computed: {
    ...mapGetters("dashboard", ["getDashboard"]),
    dates() {
      return this.days.map(day => day.date);
    },
    attributes() {
      return this.dates.map(date => ({
        highlight: true,
        dates: date,
      }));
    },
  },
  methods: {
    ...mapActions("dashboard", ["actionDashboard"]),
    toggleFilter() {
      this.filter.date_from = "";
      this.filter.date_to = "";
      this.filterShow = !this.filterShow;
    },
    async search() {},
    async clear() {},
    onDayClick(day) {
        const idx = this.days.findIndex(d => d.id === day.id);
        if (idx >= 0) {
            this.days.splice(idx, 1);
        }else{
            this.days.push({
                id: day.id,
                date: day.date,
                status: 'free',
            });
        }
    },
  },
  async mounted() {
    this.loaded = true;
    await this.actionDashboard();
    console.log(this.getDashboard)
  },
};
</script>
<style scoped >
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
</style>
