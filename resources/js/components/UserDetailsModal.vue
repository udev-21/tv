<template>
  <fwb-modal size="5xl" v-if="this.user && this.isOpen" v-show="this.isOpen" @close="closeModal" class="min-w-full">
    <template #header>
        <div class="flex items-center text-lg">
            Yo'qlama
        </div>
    </template>
    <template #body>
        {{ user.name ?? "nono" }}
        <fwb-img
            alt="Rasm yo'q"
            size="max-h-52"
            :src="`/storage/${user.avatar}`"
        />
        <!-- <fwb-checkbox  @click="console.log('changed', isRangePicker)" v-model="isRangePicker" label="Range" /> -->
        <div class="flex items-center mt-4" >
            <VueDatePicker 
                v-model="filteredDate" 
                range 
                auto-range="6" 
                :model-auto=true 
                :enable-time-picker=false 
                :auto-apply=true
                :max-date="new Date()"
                locale="uz"
                format="dd.MM.yyyy"
                @update:model-value="handlePick"
                ></VueDatePicker>
        </div>
        <fwb-accordion flush class="mt-8">
            <fwb-accordion-panel v-for="day in filteredDays" v-show="filterLogsByDay(day).length > 0">
                <fwb-accordion-header>{{ extractDay(day) }}</fwb-accordion-header>
                <fwb-accordion-content>
                    <fwb-table hoverable>
                        <fwb-table-head>
                            <fwb-table-head-cell>#</fwb-table-head-cell>
                            <fwb-table-head-cell>Kelgan</fwb-table-head-cell>
                            <fwb-table-head-cell>Ketgan</fwb-table-head-cell>
                            <fwb-table-head-cell>Binoda bo'lgan vaqti</fwb-table-head-cell>
                        </fwb-table-head>
                        <fwb-table-body>
                            <template v-for="session in filterLogsByDay(day)" :key="user.id">
                                <fwb-table-row>
                                    <fwb-table-cell class="!text-left" >{{ session.id }}</fwb-table-cell>
                                    <fwb-table-cell class="!text-left" >{{ asdf(session.in) }}</fwb-table-cell>
                                    <fwb-table-cell class="!text-left" >{{ asdf(session.out) }}</fwb-table-cell>
                                    <fwb-table-cell class="!text-left" >{{ humanizeDuration(session.duration) }}</fwb-table-cell>
                                </fwb-table-row>
                            </template>
                            
                        </fwb-table-body>
                    </fwb-table>
                </fwb-accordion-content>
            </fwb-accordion-panel>
        </fwb-accordion>
        <div v-if="this.logs.length == 0 || this.logs.length == 1 && this.logs.out == null" class="m-5 text-center text-lg">
            Ma'lumot topilmadi
        </div>
        
    </template>
  </fwb-modal>
</template>

<script >
import { FwbButton, FwbModal, FwbCheckbox } from 'flowbite-vue'

import {
    FwbTable,
    FwbTableBody,
    FwbTableCell,
    FwbTableHead,
    FwbTableHeadCell,
    FwbTableRow,
    FwbAccordion,
    FwbAccordionContent,
    FwbAccordionHeader,
    FwbAccordionPanel,
    FwbImg,
} from "flowbite-vue";

import moment from 'moment'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { watch, watchSyncEffect } from 'vue';

    export default {
        name: "UserDetailsModal",
        data() {
            return {
                isRangePicker: true,
                filteredDate: null,
                endDate: moment(),
                startDate: moment().subtract(6,'d'),
                logs: [],
            }
        },
        props: {
            'user': Object,
            'isOpen': Boolean,
        },
        mounted() {
            this.filteredDate = [this.startDate, this.endDate];
        },
        components: {
            FwbButton,
            FwbModal,
            FwbCheckbox,
            VueDatePicker,
            FwbTable,
            FwbTableBody,
            FwbTableCell,
            FwbTableHead,
            FwbTableHeadCell,
            FwbTableRow,
            FwbAccordion,
            FwbAccordionContent,
            FwbAccordionHeader,
            FwbAccordionPanel,
            FwbImg,
        },

        methods: {
            closeModal() {
                this.$emit('close')
            },

            handleSearch() {
                const from = moment(this.startDate).format('YYYY-MM-DD')
                const to = moment(this.endDate).format('YYYY-MM-DD')
                this.getLogs(from, to);
            },

            getLogs(from, to){
                axios.get(`/api/sessions/users/${this.user.id}/${from}/${to}`)
                    .then((response) => {
                        console.log(response.data);
                        this.logs = response.data;
                    })
            },

            handlePick(data) {
                this.filteredDate = data;
                this.startDate = this.filteredDate[0];
                this.endDate = this.filteredDate[1];
                this.handleSearch();
            }
        },
        computed: {
            asdf() {
                return (date) => date == null ? '-' : moment(date).format('DD.MM.YYYY HH:mm:ss');
            },

            extractDay() {
                return (date) => moment(date).format('DD.MM.YYYY');
            },

            filteredDays() {
                return [
                    moment(this.endDate),
                    moment(this.endDate).subtract(1, 'd'),
                    moment(this.endDate).subtract(2, 'd'),
                    moment(this.endDate).subtract(3, 'd'),
                    moment(this.endDate).subtract(4, 'd'),
                    moment(this.endDate).subtract(5, 'd'),
                    moment(this.endDate).subtract(6, 'd'),
                ]
            },

            filterLogsByDay() {
                return (day) => this.logs.filter( e => {
                    if (e.out == null) {
                        if (moment(Date.parse(day)).isSame(new Date, 'day')) {
                            return true;
                        }
                        return false;
                    }
                    return moment(Date.parse(day)).isSame(Date.parse(e.out), 'day')
                })
            },

            humanizeDuration() {
                return (duration) => {
                    const days = moment.duration(duration*1000).days();
                    const hours = moment.duration(duration*1000).hours();
                    const minutes = moment.duration(duration*1000).minutes();
                    const seconds = moment.duration(duration*1000).seconds();

                    console.log(days, hours, minutes, seconds);

                    let in_building_time_show = ''
                    if (days >= 1) {
                        in_building_time_show += days + ' кун ';
                    }
                    if (hours >= 1 && days < 1) {
                        in_building_time_show += hours + ' соат ';
                    }
                    if (minutes >= 1 && hours < 5) {
                        in_building_time_show += minutes + ' дакика ';
                    }
                    if (hours < 1 && minutes < 1) {
                        in_building_time_show += seconds + ' сония ';
                    }
                    return in_building_time_show;
                }
            },
        },
        watch:{
            'user': function() {
                const from = moment(this.startDate).format('YYYY-MM-DD')
                const to = moment(this.endDate).format('YYYY-MM-DD')

                this.getLogs(from, to);
            }
        }
    }

</script>