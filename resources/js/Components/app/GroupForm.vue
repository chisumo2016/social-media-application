<!--This is child Component  / Send to  GroupModal (Parent)-->
<script setup>

import Checkbox from "@/Components/Checkbox.vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import TextInput from "@/Components/TextInput.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

defineProps({
    form:Object
})

const editor = ClassicEditor;
const editorConfig ={
    toolbar: ['heading' ,'|', 'bold',
        'italic',
        'link',
        '|',
        'bulletedList',
        'numberedList',
        '|',
        'indent',
        'outdent',
        '|',
        'blockQuote',
    ],
    // balloonToolbar: ['balloonLink','balloonBlockquote','balloonIndent','balloonAlign','balloonImage']
}

</script>

<template>
    <div class="mb-3">
        <label for=""> Group Name</label>
        <TextInput
            type="email"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
        />
    </div>
    <div class="mb-3">
        <label >

            <Checkbox name="auto_approval" v-model:checked="form.auto_approval" />
            Enable Auto Approval

        </label>
    </div>
    <div class="mb-3">
        <label for="">About Group</label>
        <!-- CKEDITOR  -->
        <ckeditor :editor="editor" v-model="form.about" :config="editorConfig"></ckeditor>
<!--        <InputTextArea v-model="form.about" class="w-full"/>-->
    </div>
</template>

<style scoped>

</style>
