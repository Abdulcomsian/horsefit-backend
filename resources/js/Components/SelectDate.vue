<template>
    <div>
        <div class="relative flex items-center">
            <flat-pickr v-model="selectedDate"
                        ref="flatpickrInput"
                        :config="datepickerConfig"
                        placeholder="Please select date"
                        class="dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2.5 pl-9 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:outline-none"
            ></flat-pickr>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 class="absolute w-4 h-4 text-gray-500 left-3 pointer-events-none">
                <path
                    d="M20 4V3a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v1H3a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-1V4zm-4 2H8v2h8V6zM6 10h12v2H6v-2zm4 4h4v2H10v-2z"/>
            </svg>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref, watchEffect, computed} from 'vue';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

const emit = defineEmits(['update:modelValue']);

    const props = defineProps({
        label: {
            type: String,
            default: 'Date'
        },
        datepickerConfig: {
            type: Object,
            default: () => {}
        },
        modelValue: {
            type: String,
            default: ''
        },
        changeDate: {
            type: Function,
            default: () => {}
        }
    });

const selectedDate = computed({
    get() {
        return props.modelValue;
    },

    set(val) {
        emit('update:modelValue', val);
        props.changeDate(val);
    },
});

</script>

<style>
/* Custom styles for today's date */
.flatpickr-calendar .flatpickr-day.today {
    background-color: #FCE96A !important;
    color: #000000;
    border-radius: 12px !important;
}

/* Custom styles for selected date */
.flatpickr-calendar .flatpickr-day.selected {
    background-color: #4941B4 !important;
    color: #ffffff;
    border-radius: 12px !important;
}

/* Custom styles for date picked by the user */
.flatpickr-calendar .flatpickr-day.inRange {
    background-color: #878787 !important;
    color: #ffffff;
    border-radius: 12px !important;
}

.flatpickr-calendar .flatpickr-day:not(.selected):hover, .flatpickr-calendar .flatpickr-day:not(.selected):focus {
    background: rgba(94, 114, 228, 0.28);
    border-radius: 12px !important;
}

.flatpickr-monthDropdown-months {
    font-size: 20px !important;
    font-weight: 500 !important;
}

.numInput.cur-year {
    font-size: 20px !important;
    font-weight: 500 !important;
}

</style>

