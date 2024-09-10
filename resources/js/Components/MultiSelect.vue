<template>
    <vue-select
        v-model="selectedItems"
        :options="options"
        multiple
        :label="label"
        :track-by="label"
        :placeholder="placeholder"
        :close-on-select="closeOnSelect"
    >
    <template v-slot:no-options>
            {{ props.noOptionsMessage }}
        </template>
    </vue-select>
</template>

<script setup>
import {computed} from 'vue';
import VueSelect from 'vue-select';

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Array,
        required: true,
    },
    label: {
        type: String,
        default: 'name',
    },
    trackBy: {
        type: String,
        default: 'id',
    },
    placeholder: {
        type: String,
        default: 'Select Items',
    },
    closeOnSelect: {
        type: Boolean,
        default: true
    },
    noOptionsMessage: {
        type: String,
        default: 'Sorry, no matching options'
    }
});
const emit = defineEmits(['update:modelValue'])

const selectedItems = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        let items = [];

        value.forEach((item) => {
            items.push({[props.trackBy]: item.id, [props.label]: item[props.label]})
        });
        emit('update:modelValue', items)
    },
});
</script>

<style>

    .v-select {
        border-style: solid;
        background-color: rgb(255,255,255);
        border-width: 1px;
        border-radius: .5rem;
        padding: .5rem;
        font-size: 0.875rem;
    }

    .vs--multiple .vs__selected {
      color:#fff !important;
        background-color: #8D1CBA;
        color: rgb(255 255 255 / var(--tw-text-opacity));
        font-weight: 500;
        padding: .15rem .5rem;
        border-radius: 9999px;
        font-size: .870rem;
    }

    .vs--multiple .vs__deselect {
        fill: rgb(255 255 255);
        font-weight: 400;
        padding: .2rem;
        border-left: 1px solid;
    }

    .vs__dropdown-toggle {
        border: none;
    }

    .vs__search, .vs__search:focus {
        font-size: .875rem;
    }

</style>
