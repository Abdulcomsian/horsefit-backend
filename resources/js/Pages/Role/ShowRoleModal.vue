<script setup>
import { defineEmits, defineProps } from "vue";
import Status from "@/Components/Status.vue";


const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    modalClose: {
        type: Function,
        default: () => {
        },
    },
    selectedViewRole: {
        type: Object,
        default: null,
    }

});
const changeStatus = (statusCode) => {
    switch (statusCode){
        case 1:

            return "Active"
        case 2:
            return "Inactive"
        case 3:
            return "Blocked"
        default:
            return "Active"
    }
}

const emit = defineEmits(["close-modal"]);

</script>
<template>
    <div class="bg-white rounded-lg shadow-lg p-8 common-scroll-bar" style="max-height: 81vh; overflow: scroll">
      <h2 class="text-gray-800 text-xl font-bold mb-3 uppercase flex justify-center pt-2">View role & permissions ({{ selectedViewRole.name }})</h2>

      <div class="w-full grid grid-cols-2 gap-4 text-sm">
        <div><p><b>Name</b> : {{selectedViewRole.name}}</p></div>
        <div><p><b>status</b> : <Status :status="changeStatus(selectedViewRole.status)" /></p></div>
      </div>
      <div class="w-full mt-3 text-sm">
        <p><b>Description</b></p>
        <p class="mt-2">{{selectedViewRole.description}}</p>
      </div>

      <div class="w-full mt-4 text-sm">
        <h2 class="font-semibold col-span-3 text-xl text-gray-800 leading-tight">Permissions</h2>

        <div v-if="selectedViewRole.permissions.length > 0" class="grid grid-cols-3 gap-4 div-margin permission-list-view">
          <div v-for="permission in selectedViewRole.permissions" :key="permission.id" class="flex items-center space-x-3">
            <svg class="flex-shrink-0 w-4 h-4 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            <div>{{ permission.name }}</div>
          </div>
        </div>
        <p v-else>No permissions found</p>

      </div>
    </div>
</template>