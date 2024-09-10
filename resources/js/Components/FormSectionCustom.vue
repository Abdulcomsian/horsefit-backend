<script setup>
defineEmits(['submitted']);
</script>

<template>
    <form novalidate @submit.prevent="$emit('submitted')">
        <slot name="form"/>
    </form>
</template>
