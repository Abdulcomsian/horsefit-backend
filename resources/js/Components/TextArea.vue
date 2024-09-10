<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    modelValue: String,
    customClass: String,
    wordCount: {
        type: Boolean,
        default: false
    },
    maxWords: {
        type: Number,
        default: 5000
    },
    rows: {
        type: Number,
        default: 2
    },
    placeholder: {
        type: String,
        default: 'Enter description'
    }
});

const emit = defineEmits(['update:modelValue']);

const textarea = ref(null);

const wordCountValue = ref(0);

onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }

    const text = textarea.value.value;
    const words = text.split(/\s+/).filter(word => word.length > 0); 
    wordCountValue.value = words.length;


});

const countWords = (event) => {
  const text = event.target.value;
  const words = text.split(/\s+/).filter(word => word.length > 0);
  
  if (words.length > props.maxWords) {
    const newWords = words.slice(0, props.maxWords);
    event.target.value = newWords.join(' ') + ' ';
    wordCountValue.value = newWords.length;
    emit('update:modelValue', newWords.join(' ') + ' ');
    return;
  }
  
  wordCountValue.value = words.length;
  emit('update:modelValue', text);
}



defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea type="text"
            :rows="rows"
            :placeholder="placeholder"
           ref="textarea"
           :class="customClass ? customClass: 'dark:bg-gray-950 dark:placeholder:text-white/80 normal-case dark:text-white/80 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-primary focus:outline-none block py-1.5 text-gray-900 shadow-none ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'"
           :value="modelValue"
           @input="countWords"
    />
    <label v-if="wordCount" class="font-semibold text-xs text-[#ADB5BD]">
    <span>Word Count: {{ wordCountValue }}</span>
    <span :class="{ 'text-red-700': wordCountValue >= maxWords }"> , Words left: {{ maxWords - wordCountValue }}</span>
    </label>
</template>
  
<style scoped>  
[type="text"], [type="email"], [type="url"], [type="password"], [type="number"], [type="date"], [type="datetime-local"], [type="month"], [type="search"], [type="tel"], [type="time"], [type="week"], [multiple], textarea, select{
    font-size: 13px !important;
}

</style>