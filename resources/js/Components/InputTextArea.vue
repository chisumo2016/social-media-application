<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
    placeholder:String,

});

 const props = defineProps({
     autoResize:{
         type: Boolean,
         default:true
     }
 })

const autoResizeTextarea = ref()
const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});


defineExpose({ focus: () => input.value.focus() });

const  emit = defineEmits(['update:modelValue']);
function onInputChange($event) {
    emit('update:modelValue', $event.target.value)

     if (props.autoResize) {
         input.value.style.height = 'auto'; // Reset the height to auto
         input.value.style.height = input.value.scrollHeight + 'px'; // Set the height to match the content
     }
}
</script>

<template>
    <textarea
        class="border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        v-model="model"
        :placeholder="placeholder"
        ref="input"
        @input="onInputChange"
    ></textarea>
</template>
