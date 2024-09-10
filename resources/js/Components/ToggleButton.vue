<script setup>
import { defineEmits, ref, watch, computed, onMounted, inject} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
const swal = inject('$swal')

const props = defineProps({
    labelValue: {
        type: String,
        default: null,
    },
    modelValue: {
        type: Number,
        default: 0,
    },
    showConfirmation: {
        type: Boolean,
        default: false
    },
    confirmationMessage: {
        type: String,
        default: "You won't be able to revert this!"
    },
});

const emit = defineEmits(["update:modelValue", "showUnits", "hideUnits"]);

const toggleValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        if (props.showConfirmation && value == false) {
            swal({
                title: "Are you sure?",
                text: props.confirmationMessage,
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonText: "Yes, disable it!",
                customClass: {
                    confirmButton: "inline-block mr-3 px-6 py-3 m-0 ml-2 text-xs font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-primary shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85",
                    cancelButton: "inline-block px-6 py-3 m-0 font-bold text-center uppercase align-middle transition-all bg-gray-200 border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 text-slate-800",
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    emit('update:modelValue', value);
                    emit('hideUnits');
                    toggleInput.checked = false;
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    props.modelValue = 1;
                    emit('update:modelValue', 1);
                    emit('showUnits');
                    toggleInput.checked = true;
                } else {
                    props.modelValue = 1;
                    emit('update:modelValue', 1);
                    emit('showUnits');
                    toggleInput.checked = true;
                }
            });
        } else {
            emit('update:modelValue', value);
            emit('showUnits');
        }
    },
});

let toggleInput;

onMounted(() => {
    toggleInput = document.querySelector('.peer');
});

</script>

<template>
    <label class="flex items-center cursor-pointer m-0 p-0">
        <InputLabel :value="labelValue" customClass="m-0 p-0 inline-block font-bold text-sm text-slate-700 dark:text-white/80" style="margin-top: 0px; padding: 0px 8px 0px 0px;"/>
        <div class="relative flex items-center cursor-pointer">
            <input type="checkbox" :checked="modelValue" v-model="toggleValue" class="peer hidden" />
            <div
                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary">
            </div>
        </div>
    </label>
</template>

<style scoped>
.peer:checked~.peer-checked\:bg-primary {
    --tw-bg-opacity: 1;
    background-color: rgb(63, 46, 218)
}
</style>
