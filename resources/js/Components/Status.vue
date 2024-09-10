<script setup>
defineProps({
    status: {
        type: String,
        default: 'Active'
    }
})

const style = (status) => {
    switch (status) {
        case 'Active':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-green-700 bg-green-200"
        case 'Published':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-green-700 bg-green-200"
        case 'Posted':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-green-700 bg-green-200"
        case 'New':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-green-700 bg-green-200"
        case 'Renewal':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-yellow-700 bg-yellow-200"
        case 'Inactive':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-yellow-700 bg-yellow-200"
        case 'Unpublished':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-yellow-700 bg-yellow-200"
        case 'Pending':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-yellow-700 bg-yellow-200"
        case 'Declined':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-red-700 bg-red-200"
        case 'Blocked':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-red-700 bg-red-200"
        case 'Old Record':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-red-700 bg-red-200"
        case 'Free':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-yellow-700 bg-yellow-200"
        case 'Paid':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none bg-green-200 text-green-700"
        case 'Unpaid':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-red-700 bg-red-200"
            case 'Graded':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-green-700 bg-green-200"
        case 'Reattempt':
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-red-700 bg-red-200"
        default:
            return "py-2 px-2 text-xs rounded-1 inline-block whitespace-nowrap text-center align-baseline font-bold capitalize leading-none text-green-700 bg-green-200"
    }
}

</script>
<template>
    <span class="left-align-on-print" :class="style(status)">{{ status }}</span>
</template>

<style scoped>
@media print {
    .left-align-on-print {
        padding: 0px !important;
        margin: 0px !important;
    }
}
</style>
