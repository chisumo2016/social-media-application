<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon, ArrowDownTrayIcon,MapPinIcon} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";
import CommentList from "@/Components/app/CommentList.vue";
import {computed} from "vue";
import UrlPreview from "@/Components/app/UrlPreview.vue";


const props = defineProps({
    post: Object
})

const  authUser = usePage().props.auth.user;
const  group    = usePage().props.group;



const emit = defineEmits(['editClick' ,'attachmentClick'])

const postBody = computed(() =>  {
        /**Detecting the Hash Tag*/
        let content = props.post.body.replace(
            // /(#\w+)(?![^<]*<\/a>)/g,
            /(?:(\s+)|<p>)((#\w+)(?![^<]*<\/a>))/g,
            (match, group1, group2) => {  //1 is white space
               // console.log('"${match}"' , '"${group1}"' ,'"${group2}"')
                const encodedGroup = encodeURIComponent(group2);
                return `${group1 || ''}<a href="/search/${encodedGroup}" class="hashtag">${group2}</a>`;

                //return `<a href="/search/${encodedGroup}" class="hashtag">${group}</a>`;

            }
         )
        // '<a href="/search/$1">$1</a>')

            return content;
  })

const  isPinned = computed(() => {

    if (group?.id){
        return group?.pinned_post_id === props.post.id
    }

    return authUser?.pinned_post_id === props.post.id

})


function openEditModal() {
    emit('editClick', props.post)
}

function deletePost() {
    if (window.confirm("Are you sure you want to delete this post  ?")){

        router.delete(route('post.destroy',props.post),{
            preserveScroll: true
        })
    }
}

/*Preview Attachment**/
function openAttachment(index) {
    console.log(index)
    emit('attachmentClick', props.post , index) // passing  post to a paraent, this section is chhil
}

function sendReaction() {
    axiosClient.post(route('post.reaction', props.post),{
        reaction:'like'
    })
        .then(({data}) =>{
            props.post.current_user_has_reaction = data.current_user_has_reaction
            props.post.num_of_reactions = data.num_of_reactions

            console.log(data)
        })
}

function pinUnPinPost() {
    const form = useForm({
        forGroup: group?.id
    })

    let isPinned = false;
    if (group?.id){

        isPinned = group?.pinned_post_id === props.post.id;

    }else{

        isPinned = authUser?.pinned_post_id === props.post.id;
    }

    form.post(route('post.pinUnpin', props.post.id),{
        preserveScroll : true,
        onSuccess: () =>{
            if (group?.id){
                group.pinned_post_id = isPinned ? null : props.post.id
            }else {
                authUser.pinned_post_id = isPinned ? null : props.post.id
            }
        }
    })


    /*axiosClient.post(route('post.pinUnpin', props.post.id))
        .then(res =>{
            props.post.pinned = !props.post.pinned
        })*/
}

</script>

<template>
<!--    Post Header-->
<div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between  mb-3">
        <!--    Post User Header Component   -->
       <PostUserHeader :post="post"/>

       <!--  Drop down Menu for Edit/Delete   -->
       <div class="flex items-center gap-2">

<!--           {{ post.pinned ? 'Pinned' : '' }}-->
               <div v-if="isPinned" class="flex items-center text-sm">
                   <MapPinIcon
                       class="h-3 w-3"
                       aria-hidden="true" />
                   pinned
               </div>

           <EditDeleteDropdown
               @edit="openEditModal"
               @delete="deletePost"
               :user="post.user"
               :post="post"
               @pin="pinUnPinPost"/>

       </div>

    </div>

<!--    Read More Section   post.body -->
    <div class="mb-3">
        <ReadMoreReadLess :content="postBody"/>

        <!--    Preview  -->
        <!--<div v-if="post.preview && post.preview.title"></div> -->
           <UrlPreview :preview="post.preview" :url="post.preview_url"/>



   </div>

<!--  Attachment Section -->
    <div class="grid   gap-3 mb-3" :class="[post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2']">
            <!-- Passiing the Attachments---->
        <PostAttachments :attachments="post.attachments" @attachmentClick="openAttachment"/>

    </div>
    <Disclosure v-slot="{ open }">
        <!-- Like & Comment Icon Reaction-->
        <div class="flex gap-2">

            <button
                @click="sendReaction"
                class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 py-2 px-4 flex-1 rounded-lg"
                :class="[
                 post.current_user_has_reaction ?
                 'bg-sky-100 hover:bg-sky-200' :
                 'bg-gray-100 hover:bg-gray-200'
            ]"
            >

                <HandThumbUpIcon class="w-5 h-5"/>
                <span class="mr-2">({{ post.num_of_reactions}})</span>
                {{  post.current_user_has_reaction ? 'Unlike' : 'Like' }}
            </button>
            <DisclosureButton
                class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 py-2 px-4 flex-1 rounded-lg"
            >
                <ChatBubbleLeftRightIcon class="w-5 h-5"/>
                <span class="mr-2">({{ post.num_of_comments}})</span>
                Comment

            </DisclosureButton>
        </div>

        <!--Comment Section-->
        <DisclosurePanel class="mt-3 max-h-[400px] overflow-auto comment-list ">
            <!--Comment Create Section-->
             <CommentList :post="post" :data="{comments: post.comments}"/>
        </DisclosurePanel>
    </Disclosure>

</div>
</template>

