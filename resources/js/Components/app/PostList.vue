<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {onMounted, onUpdated, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";
import axiosClient from "@/axiosClient.js";

// Uses
const page = usePage();

const allPosts = ref({  /*Read More**/
    data: page.props.posts.data,
    next: page.props.posts.links.next
})
const props = defineProps({
    posts: Array
})





const authUser = usePage().props.auth.user;


const showEditModal = ref(false)
const showAttachmentModal = ref(false)
const editPost = ref({})
const previewAttachmentPost = ref({})
const loadMoreIntersect  = ref(null)


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

/**Load More**/

function loadMore() {
    if (!allPosts.value.next){
        return;
    }

    axiosClient.get(allPosts.value.next)  //url
        .then(({data}) => {
            //console.log( allPosts.value.data, data.data)
            allPosts.value.data = [...allPosts.value.data, ...data.data]
            allPosts.value.next = data.links.next

        })
}


onMounted(() =>{
    const observer = new IntersectionObserver(
        (entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
            rootMargin: '-250px 0px 0px 0px'
        })

    observer.observe(loadMoreIntersect.value)
})

</script>

<template>


<!-- instead of posts v-for="post of posts", we gonna iterate allPosts  <pre>{{ allPosts.data.length}}</pre>  -->
<div class="overflow-auto">

    <PostItem v-for="post of allPosts.data" :key="post.id" :post="post"
              @editClick="openEditModal"
              @attachmentClick="openAttachmentPreviewModal" />

    <!--    Load More-->
    <div ref="loadMoreIntersect"></div>

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
