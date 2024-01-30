<script setup>
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import {useForm} from "@inertiajs/vue3";

const   postCreating = ref(false);

const  newPostForm = useForm({
    body: ''
})

function createPost() {
    newPostForm.post(route('post.create'),{
        onSuccess : () =>{
            newPostForm.reset()
        }
    })
}

</script>

<template>
    <div class="p-4 bg-white rounded-lg border mb-4">
        <InputTextArea
            @click="postCreating = true"
            rows="1"
            class="mb-3 w-full"
            v-model="newPostForm.body"
            placeholder="Click here to create new Post" />

        <div v-if="postCreating" class="flex gap-2 justify-between">
            <button
                type="button"
                class="rounded-md
                        bg-indigo-600
                        px-3 py-2 text-sm
                        font-semibold text-white
                        shadow-sm hover:bg-indigo-500
                        focus-visible:outline
                        focus-visible:outline-2
                        focus-visible:outline-offset-2
                        focus-visible:outline-indigo-600 relative">
                <input type="file" class="absolute  left-0 top-0 right-0 bottom-0 opacity-0">
                Attach  Files
            </button>
            <button
                @click="createPost"
                type="submit"
                class="rounded-md
                        bg-indigo-600
                        px-3 py-2 text-sm
                        font-semibold text-white
                        shadow-sm hover:bg-indigo-500
                        focus-visible:outline
                        focus-visible:outline-2
                        focus-visible:outline-offset-2
                        focus-visible:outline-indigo-600"
            >Submit</button>
        </div>
    </div>
</template>

<style scoped>

</style>
