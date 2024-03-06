<script setup>

import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import InputTextArea from "@/Components/InputTextArea.vue";
import {ChatBubbleLeftEllipsisIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import IndigoButton from "@/Components/app/IndigoButton.vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import {usePage ,Link} from "@inertiajs/vue3";
import {ref} from "vue";
import axiosClient from "@/axiosClient.js";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";


const authUser = usePage().props.auth.user;

const props = defineProps({
    post: Object,
    data: Object, // sub comments
    parentComment:{
        type:[Object , null],
        default: null
    }
})

const emit = defineEmits(['commentCreate','commentDelete'])

const newCommentText = ref('')

const editingComment  = ref()
function createComment() {

    axiosClient.post(route('post.comment.create', props.post),{
        comment: newCommentText.value,
        parent_id: props.parentComment?.id || null  // parentId
    })
        .then(({data}) =>{
            newCommentText.value = '';
            props.data.comments.unshift(data)
            if (props.parentComment){
                props.parentComment.num_of_comments++;
            }
            props.post.num_of_comments++;
            emit('commentCreate',data)
            //console.log(data);
        })
}

function startCommentEdit (comment) {
    editingComment.value = {
        id: comment.id,
        comment: comment.comment.replace(/<br\s*\/?>/gi,'\n') // <br /> , <br > <br>
    }
   // console.log(comment);
}
function updateComment() {
    axiosClient.put(route('comment.update', editingComment.value.id),editingComment.value)
        .then(({data}) =>{
            editingComment.value = null
            console.log(data)
            props.data.comments = props.data.comments.map((c) =>{
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

            const commentIndex = props.data.comments.findIndex(c => c.id === comment.id)
            props.data.comments.splice(commentIndex, 1) // delete 1
            //props.data.comments = props.comment.filter(c => c.id != comment.id )
            if (props.parentComment){
                props.parentComment.num_of_comments--;
            }
              props.post.num_of_comments--;
            emit('commentDelete',comment)

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

function onCommentCreate(comment) {
    if (props.parentComment) {
        props.parentComment.num_of_comments++
    }
    emit('commentCreate', comment)
}

function onCommentDelete(comment) {
    if (props.parentComment) {
        props.parentComment.num_of_comments--
    }
    emit('commentDelete', comment)
}
</script>

<template>
    <div class="flex gap-2 mb-3">

        <Link :href="route('profile', authUser.username)">

            <img :src="authUser.avatar_url" alt=""
                 class="w-[40px] rounded-full border border-2  transition-all hover:border-blue-500">

        </Link>

        <!--  Comment Text Area Component Section   -->
        <div class="flex flex-1">
            <InputTextArea v-model="newCommentText" rows="1" class="w-full max-h-[160px] resize-none rounded-r-none"></InputTextArea>
            <IndigoButton @click="createComment" class="rounded-l-none w-[100px] ">Submit</IndigoButton>
        </div>

    </div>
    <div>
        <!--    Editing Comment  -->
        <div v-for="comment of data.comments" :key="comment.id" class="mb-4">

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
                <EditDeleteDropdown
                    @edit="startCommentEdit(comment)"
                    @delete="deleteComment(comment)"
                    :user="comment.user"
                    :post="post"
                    :comment="comment"/>
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

                <Disclosure>
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

                        <DisclosureButton
                            @click=""
                            class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100  rounded-lg">
                            <ChatBubbleLeftEllipsisIcon class="w-3 h-3 mr-1"/>
                            <span class="mr-2">({{ comment.num_of_comments}})</span>
                            comments
                        </DisclosureButton>
                    </div>
                    <DisclosurePanel class="mt-3">
                    <!--   children comments-->
                        <CommentList
                            :post="post"
                            :data="{comments: comment.comments}"
                            :parent-comment="comment"
                            @comment-create="onCommentCreate"
                            @comment-delete="onCommentDelete"/>
                                            <!--  onCommentCreate - coming fromm childrren  -->
                    </DisclosurePanel>
                </Disclosure>

            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
