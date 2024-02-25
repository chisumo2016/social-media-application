<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";

const props = defineProps({
    posts: Array
})


const authUser = usePage().props.auth.user;


const showEditModal = ref(false)
const showAttachmentModal = ref(false)
const editPost = ref({})
const previewAttachmentPost = ref({})

function openEditModal(post) {
    editPost.value = post;
    showEditModal.value = true;

}

function openAttachmentPreviewModal(post, index) { //current file I am clicking
    //debugger;
    previewAttachmentPost.value = {
        post,
        index
    }
    showAttachmentModal.value = true;
}

function onModalHide() { //reset the post
    console.log('1111')
    editPost.value = {
        id: null,
        body:'',
        user: authUser
    }
}
</script>

<template>

<div class="overflow-auto">
    <PostItem v-for="post of posts" :key="post.id" :post="post"
              @editClick="openEditModal"
              @attachmentClick="openAttachmentPreviewModal" />

    <!--    Post Modal-->
    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>

    <!--    Preview Attachment Modal-->

    <AttachmentPreviewModal
        :attachments="previewAttachmentPost.post?.attachments || []"
        v-model="showAttachmentModal"
        v-model:index="previewAttachmentPost.index"

    />
</div>

</template>

<style scoped>

</style>
