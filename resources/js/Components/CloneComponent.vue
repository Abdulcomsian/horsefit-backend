<template>
  <div>
    <slot :inputData="clonedInputData" :addInputField="addInputField" :addNewInputField="addNewInputField" :removeInputField="removeInputField"
      :isRemoveButtonDisabled="isRemoveButtonDisabled">
    </slot>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
  itemArray: {
    type: Array,
    default: null,
  },
  initialFields: {
    type: Number,
    default: 1,
  },
});

const emit = defineEmits(['clone-input-data', 'clone-input-data-sidebar', 'delete-sub-unit', 'delete-sub-package', 'clone-input-data-length', 'on-mount-clone-component-added', 'delete-sub-addon', 'one-unit-at-once']);

const clonedInputData = ref([]);

const addInputField = () => {
  clonedInputData.value.push({});
  emit('clone-input-data', clonedInputData.value)
  emit('clone-input-data-length', clonedInputData.value.length)
};

const addNewInputField = (value) => {
  if (clonedInputData.value.length === value) {
    clonedInputData.value.push({});
    emit('clone-input-data', clonedInputData.value)
    emit('clone-input-data-length', clonedInputData.value.length)
  } else {
    emit('one-unit-at-once', true)
  }
};

const isRemoveButtonDisabled = computed(() => {
  return clonedInputData.value.length === 1;
});

const removeInputField = async (index) => {

    clonedInputData.value.splice(index, 1);
    emit('clone-input-data', clonedInputData.value);
    emit('clone-input-data-length', clonedInputData.value.length)
};

onMounted(() => {
  if (clonedInputData.value.length === 0) {
    for (let i = 0; i < props.initialFields; i++) {
      emit('on-mount-clone-component-added', 'yes');
      addInputField();
    }
  }
  if (props.itemArray.length > 0) {
    clonedInputData.value = [...props.itemArray];
    emit('clone-input-data', clonedInputData.value);

  }
});

</script>
