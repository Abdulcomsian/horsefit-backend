<template>
    <div>
        <vue-select
            id="select-single"
            v-model="selectedOption"
            :options="options"
            :label="label"
            :track-by="trackBy"
            :class="customClass"
            :placeholder="placeholder"
            :disabled="disabled"
        >
        <template v-slot:no-options>
            {{ props.noOptionsMessage }}
        </template>
    </vue-select>
    </div>
</template>

<script setup>
import {computed} from 'vue';
        const props = defineProps({
            modelValue: {
                type:String,
                default:null
            },
            options: {
                type:Object,
                default:null
            },
            placeholder: {
                type:String,
                default: 'Select option'
            },
            label: {
                type:String,
                default: 'name'
            },
            trackBy: {
                type:String,
                default: 'id'
            },
            customClass: {
               type: String,
               default: "dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:outline-none",
            },
            disabled: {
                type: Boolean,
                default: false
            },
            noOptionsMessage: {
                type: String,
                default: 'Sorry, no matching options'
            }
        });

        const emit = defineEmits(['update:modelValue']);

        const selectedOption = computed({
            get(){
                return props.options.filter((option) => {
                    return option.id === props.modelValue
                })
            },
            set(value){
                emit('update:modelValue', value.id)
            }
        });
</script>
<style>
.vs__dropdown-toggle {
    border: none;
    padding: 0 0 0px 0px !important;
}
.v-select{
    font-size: 13px;
    //background-color: #6b6d7b !important;
    //border: 1px #d8d8d8 solid;
    //border-radius: 12px;
    width: 100%;
}
.vs__clear{
    display: none;
}
.vs__search {
    color: #6b6d7b;
    font-size: 14px !important;
    padding: 0px !important;
    margin: 2px 0px !important;
}
.vs_dropdown-option{
    padding-top: 3px;
    padding-bottom: 3px;
}
.vs--single .vs__selected,
.vs__selected-options {
    padding: 2px 0 0 0px;
    margin: 0;
    font-size: 16px;
}

.vs__selected-options{
    padding: 0px 0px 0px 2px;
}
.vs--searchable .vs__dropdown-toggle{
    cursor: pointer !important;
    display: flex;
    align-items: baseline;
    gap: 12px;
}

.vs__dropdown-menu{
    overflow: hidden !important;
}
.vs__dropdown-menu:hover{
    overflow-y: scroll !important;
}
.vs__dropdown-menu::-webkit-scrollbar-track
{
    border-radius: 10px !important;
    background-color: #ffffff !important;
}
.vs__dropdown-menu::-webkit-scrollbar
{
    width: 2px !important;
    background-color: #ffffff !important;
}
.vs__dropdown-menu::-webkit-scrollbar-thumb
{
    border-radius: 10px !important;
    background-color: #929292 !important;
}
.vs__dropdown-menu::-webkit-scrollbar-track:hover{
    background-color: #AEB6FF !important;
}

#vs2__listbox {
  background-color: white !important;
  border-radius: 10px;
}
#vs2__listbox ul li:hover {
  background-color: #8D1CBA !important;
}
</style>
