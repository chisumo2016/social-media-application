<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChatBubbleLeftEllipsisIcon, ChatBubbleLeftRightIcon, HandThumbUpIcon, ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router, usePage} from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import InputTextArea from "@/Components/InputTextArea.vue";

import IndigoButton from "@/Components/app/IndigoButton.vue";
import {ref} from "vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";


const props = defineProps({
    post: Object
})

const authUser = usePage().props.auth.user;
const editingComment  = ref()

const emit = defineEmits(['editClick' ,'attachmentClick'])

const newCommentText = ref('')

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

function createComment() {

    axiosClient.post(route('post.comment.create', props.post),{
        comment: newCommentText.value
    })
        .then(({data}) =>{
            newCommentText.value = '';
            props.post.comments.unshift(data)
            props.post.num_of_comments++;
            console.log(data);
        })
}

function startCommentEdit (comment) {
    editingComment.value = {
        id: comment.id,
        comment: comment.comment.replace(/<br\s*\/?>/gi,'\n') // <br /> , <br > <br>
    }
    console.log(comment);
}

function updateComment() {
    axiosClient.put(route('comment.update', editingComment.value.id),editingComment.value)
        .then(({data}) =>{
            editingComment.value = null
            console.log(data)
            props.post.comments = props.post.comments.map((c) =>{
                 if (c.id === data.id){ //update single comments
                     return data
                 }
                 return  c;
            })
        })
}
function deleteComment(comment) {
    if (!window.confirm('Are you sure you want to delete this comment ? ')){
        return false;
    }
    axiosClient.delete(route('comment.delete', comment.id))
        .then(({data}) =>{
            props.post.comments = props.post.comment.filter(c => c.id != comment.id )
            props.post.num_of_comments--;

        })
}

function sendCommentReaction(comment) {
    axiosClient.post(route('comment.reaction',comment.id),{
        reaction:'like'
    })
        .then(({data}) =>{
            comment.current_user_has_reaction = data.current_user_has_reaction
            comment.num_of_reactions = data.num_of_reactions

            console.log(data)
        })
}

</script>

<template>
<!--    Post Header-->
<div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between  mb-3">
        <!--    Post User Header Component   -->
       <PostUserHeader :post="post"/>

       <!--  Drop down Menu for Edit/Delete   -->
       <EditDeleteDropdown @edit="openEditModal" @delete="deletePost" :user="post.user"/>
    </div>

<!--    Read More Section    -->
    <div class="mb-3">
        <ReadMoreReadLess :content="post.body"/>
    </div>

<!--  Attachment Section -->
    <div class="grid   gap-3 mb-3" :class="[post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2']">

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
        <DisclosurePanel class="mt-3">
            <!--Comment Create Section-->
                <div class="flex gap-2 mb-3">
                    <a href="javascript:void(0)">

                        <img :src="authUser.avatar_url" alt=""
                             class="w-[40px] rounded-full border border-2  transition-all hover:border-blue-500">

                    </a>

                    <!--  Comment Text Area Component Section   -->
                    <div class="flex flex-1">
                        <InputTextArea v-model="newCommentText" rows="1" class="w-full max-h-[160px] resize-none rounded-r-none" placeholder="Enter your comment here"></InputTextArea>
                        <IndigoButton @click="createComment" class="rounded-l-none w-[100px] ">Submit</IndigoButton>
                    </div>

                </div>
            <div>
                <!--    Editing Comment  -->
                <div v-for="comment of post.comments" :key="comment.id" class="mb-4">

                    <div class="flex justify-between gap-2">
                        <div class="flex gap-2">
                            <a href="javascript:void(0)">

                                <img :src="comment.user.avatar_url" alt=""
                                     class="w-[40px] rounded-full border border-2  transition-all hover:border-blue-500">

                            </a>
                            <div>
                                <h4 class="font-bold">
                                    <a href="javascript:void(0)" class="hover:underline">{{ comment.user.name}}</a>

                                </h4>
                                <small class="text-sm text-gray-400 ">{{ comment.updated_at}}</small>
                            </div>
                        </div>

                        <!--  Visible if ur authenticated /owner of comment section -->
                        <EditDeleteDropdown @edit="startCommentEdit(comment)" @delete="deleteComment(comment)" :user="comment.user"/>
                    </div>
                    <div class="pl-12">
                        <!--  Visible when editing  comment section -->
                        <div v-if="editingComment && editingComment.id === comment.id">
                            <InputTextArea v-model="editingComment.comment" rows="1" class="w-full max-h-[160px] resize-none " placeholder="Enter your comment here"></InputTextArea>

                            <div class="flex justify-end gap-2">
                                <button @click="editingComment = null" class="rounded-r-none text-indigo-500" >Cancel</button>
                                <IndigoButton @click="updateComment" class="w-[100px]">Update</IndigoButton>
                            </div>
                        </div>

                        <!--  Comment List Section   {{ comment.comment }} -->
                        <ReadMoreReadLess v-else content-class="text-sm  flex flex-1" :content="comment.comment"/>

                        <div class="mt-1 flex gap-2">
                            <button
                                @click="sendCommentReaction(comment)"
                                class="flex items-center text-xs text-indigo-500 py-0.5 px-1 rounded-lg"
                                :class="[
                                         comment.current_user_has_reaction ?
                                         'bg-indigo-50 hover:bg-indigo-100' :
                                         'hover:bg-indigo-50'
                                    ]">
                                <HandThumbUpIcon class="w-3 h-3 mr-1"/>

                                <span class="mr-2">{{ comment.num_of_reactions}}</span>
                                {{  comment.current_user_has_reaction ? 'unlike' : 'like' }}

                            </button>

                            <button class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100  rounded-lg">
                                <ChatBubbleLeftEllipsisIcon class="w-3 h-3 mr-1"/>
                                reply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>

</div>
</template>

