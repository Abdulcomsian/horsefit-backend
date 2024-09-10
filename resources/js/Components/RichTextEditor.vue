<script setup>
import Editor from "@tinymce/tinymce-vue";
import { defineProps } from "vue";

const props = defineProps({
    height: {
        type: Number,
        default: 360,
    },
});

const editorConfig = {
    plugins: "code lists",
    height: props.height,
    toolbar: [
    { name: 'history', items: [ 'undo', 'redo' ] },
    { name: 'styles', items: [ 'styles' ] },
    { name: 'formatting', items: [ 'bold', 'italic' ] },
    { name: 'alignment', items: [ 'alignleft', 'aligncenter', 'alignright', 'alignjustify' ] },
    { name: 'indentation', items: [ 'outdent', 'indent' ] },
    { name: 'lists', items: [ 'numlist', 'bullist' ] },
  ]

};
</script>
<template>
    <Editor :api-key="$page.props.apiRichTextEditor" :init="editorConfig"/>
</template>
