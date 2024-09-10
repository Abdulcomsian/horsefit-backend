<script setup>
defineProps({
    value: String,
    customClass: String,
    helpingText: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <label :class="customClass ? customClass: 'inline-block mb-1 ml-0 font-bold text-sm text-slate-700 dark:text-white/80'" style="margin-top:5px">
        <span v-if="value">{{ value }}
            <span class="text-[8px] font-semibold text-[#ADB5BD]"> {{ helpingText }}</span>
        </span>
        <span v-else><slot /></span>
    </label>
</template>
