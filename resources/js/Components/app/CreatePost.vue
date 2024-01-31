<script setup>
import {ref} from "vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import PostModal from "@/Components/app/PostModal.vue";

const authUser = usePage().props.auth.user;
const   showModal = ref(false);
const   newPost = ref({
    id: null,
    body:'',
    user: authUser
});

// const  newPostForm = useForm({
//     body: ''
// })

function showCreatePostModal() {
  showModal.value = true
}

// function createPost() {
//     newPostForm.post(route('post.create'),{
//         onSuccess : () =>{
//             newPostForm.reset()
//         }
//     })
// }

</script>

<template>
    <div class="p-4 bg-white rounded-lg border mb-4">
        <div
            @click="showCreatePostModal"
            rows="1"
            class="py-2 px-3 border-2 border-gray-200 text-gray-400 rounded-md  mb-3 w-full"
             >Click here to create new Post</div>

<!--        <div class="flex gap-2 justify-between">-->
<!--            <button-->
<!--                type="button"-->
<!--                class="rounded-md-->
<!--                        bg-indigo-600-->
<!--                        px-3 py-2 text-sm-->
<!--                        font-semibold text-white-->
<!--                        shadow-sm hover:bg-indigo-500-->
<!--                        focus-visible:outline-->
<!--                        focus-visible:outline-2-->
<!--                        focus-visible:outline-offset-2-->
<!--                        focus-visible:outline-indigo-600 relative">-->
<!--                <input type="file" class="absolute  left-0 top-0 right-0 bottom-0 opacity-0">-->
<!--                Attach  Files-->
<!--            </button>-->
<!--            <button-->
<!--                @click="createPost"-->
<!--                type="submit"-->
<!--                class="rounded-md-->
<!--                        bg-indigo-600-->
<!--                        px-3 py-2 text-sm-->
<!--                        font-semibold text-white-->
<!--                        shadow-sm hover:bg-indigo-500-->
<!--                        focus-visible:outline-->
<!--                        focus-visible:outline-2-->
<!--                        focus-visible:outline-offset-2-->
<!--                        focus-visible:outline-indigo-600"-->
<!--            >Submit</button>-->
<!--        </div>-->

        <!--    Post Modal-->
        <PostModal :post="newPost" v-model="showModal"/>
    </div>
</template>

<style scoped>

</style>
