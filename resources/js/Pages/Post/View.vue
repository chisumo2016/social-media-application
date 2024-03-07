<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref} from "vue";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {usePage} from "@inertiajs/vue3";


defineProps({
    post:Object
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
    editPost.value = {
        id: null,
        body:'',
        user: authUser
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8 w-[600px] mx-auto h-full overflow-auto">
            <PostItem
                :post="post"
                @edit-click="openEditModal"
                @attachment-click="openAttachmentPreviewModal"/>
        </div>

        <!--    Post Modal-->
        <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>

        <!--    Preview Attachment Modal-->
        <AttachmentPreviewModal
            :attachments="previewAttachmentPost.post?.attachments || []"
            v-model="showAttachmentModal"
            v-model:index="previewAttachmentPost.index"

        />
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
