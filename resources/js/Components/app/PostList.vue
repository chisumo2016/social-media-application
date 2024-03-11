<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {onMounted, onUpdated, ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";
import axiosClient from "@/axiosClient.js";

// Uses
const page = usePage();

const allPosts = ref({  /*Read More**/
    data: [],  //page.props.posts.data
    next: null  //page.props.posts.links.next
})
const props = defineProps({
    posts: Array
})

watch(() => page.props.posts , () =>{  /*this get changed page.props.posts*/
    //console.log(allPosts.value.data)
    //console.log(page.props.posts.data,)
        if (page.props.posts){
            /*Update all old  posts*/
            allPosts.value ={
                data: page.props.posts.data,
                next: page.props.posts.links?.next
            }
        }

}, { deep: true, immediate: true})



const authUser = usePage().props.auth.user;
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

const showEditModal = ref(false)
const showAttachmentModal = ref(false)
const editPost = ref({})
const previewAttachmentPost = ref({})
const loadMoreIntersect  = ref(null)




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
            console.log(data)
            //console.log( allPosts.value.data, data.data)
            allPosts.value.data = [...allPosts.value.data, ...data.data]
            allPosts.value.next = data.links.next

        })
}

//hooks

// onUpdated(() =>{
//     console.log("onUpdated")
//     allPosts.value ={
//         data: page.props.posts.data,
//         next: page.props.posts.links.next
//     }
// })

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
<!--    <pre>{{allPosts.data.length}}</pre>-->
<!--    111111111111-->
<!--    <pre>{{ posts}}</pre>-->
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
