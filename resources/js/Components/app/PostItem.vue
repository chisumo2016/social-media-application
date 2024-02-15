<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import {PencilIcon, TrashIcon,  EllipsisVerticalIcon} from '@heroicons/vue/20/solid'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon,ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router, usePage} from "@inertiajs/vue3";
import {isImage} from "@/Helpers/helpers.js";
import {PaperClipIcon} from "@heroicons/vue/20/solid/index.js";
import axiosClient from "@/axiosClient.js";
import InputTextArea from "@/Components/InputTextArea.vue";

import IndigoButton from "@/Components/app/IndigoButton.vue";
import {ref} from "vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue"; //'../../Helpers/helpers.js

const props = defineProps({
    post: Object
})

const authUser = usePage().props.auth.user;

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
</script>

<template>
<!--    Post Header-->
<div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between  mb-3">
        <!--    Post User Header Component   -->
       <PostUserHeader :post="post"/>

       <!--    Drop down    -->
       <Menu as="div" class="relative inline-block text-left z-10">
                    <div>
                        <MenuButton
                            class="w-8 h-8 rounded-full hover:bg-black/5 transition flex items-center justify-center"
                        >
                            <EllipsisVerticalIcon
                                class="w-4 h-4"
                                aria-hidden="true"
                            />
                        </MenuButton>
                    </div>

                    <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                    >
                        <MenuItems
                            class="absolute right-0 mt-1 z-20 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                        >
                            <div class="px-1 py-1">
                                <MenuItem v-slot="{ active }">
                                    <button
                                        @click="openEditModal"
                                            :class="[
                                                  active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                            >
                                        <PencilIcon

                                            class="mr-2 h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        Edit
                                    </button>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                    <button
                                        @click="deletePost"
                                        :class="[
                                          active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                        ]"
                                    >
                                        <TrashIcon

                                            class="mr-2 h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        Delete
                                    </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>
    </div>

<!--    Read More Section    -->
    <div class="mb-3">
        <ReadMoreReadLess :content="post.body"/>
    </div>

<!--  Attachment Section -->
    <div class="grid   gap-3 mb-3" :class="[post.attachments.length == 1 ? 'grid-cols-1' : 'grid-cols-2']">

        <template v-for="(attachment,index) of post.attachments.slice(0,4)">

            <!--  Preview Attachment -->
            <div

                @click="openAttachment(index)"
                 class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">

                <!--  Render max 3   -->
                <div v-if="index == 3 && post.attachments.length >4 "
                     class="absolute
                            left-0 top-0
                            right-0 bottom-0
                            z-10 bg-black/60
                            text-white flex items-center justify-center text-2xl">
                    + {{ post.attachments.length -4 }} more
                </div>

                <!--  Download Image url /attachments   -->
                <a @click.stop :href="route('post.download',attachment)"
                   class="z-20 opacity-0
                          group-hover:opacity-100
                          transition-all w-8 h-8
                          flex items-center justify-center
                          text-gray-100 bg-gray-700 rounded
                          absolute right-2 top-2 cursor-pointer hover:bg-gray-800">
                    <ArrowDownTrayIcon class="w-4 h-4 mr-2"/>
                </a>

                <!--    Attachment   -->
                <img v-if="isImage(attachment)"
                     :src="attachment.url" alt=""
                     class="object-contain aspect-square">

                <template v-else class="flex flex-col justify-center items-center">
                    <PaperClipIcon  class="w-4 h-4 mr-2"/>

                    <small>{{ attachment.name }}</small>
                </template>
            </div>
        </template>
    </div>
    <Disclosure v-slot="{ open }">
        <div class="flex gap-2">
            <!-- Like Icon Reaction-->
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

                <div v-for="comment of post.comments" :key="comment.id" class="mb-4">
                    <div class="flex  gap-2">
                        <a href="javascript:void(0)">

                            <img :src="comment.user.avatar_url" alt=""
                                 class="w-[40px] rounded-full border border-2  transition-all hover:border-blue-500">

                        </a>
                        <div>
                            <h4 class="font-bold">
                                <a href="javascript:void(0)" class="hover:underline">{{ comment.user.name}}</a>

                            </h4>
                            <small class="text-sm text-gray-400 ">{{  comment.updated_at}}</small>
                        </div>
                    </div>

                    <!--  Comment List Section   {{ comment.comment }} -->
                    <ReadMoreReadLess
                        content-class="text-sm  flex flex-1 ml-12"
                        :content="comment.comment"/>

                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>

</div>
</template>

<style scoped>

</style>
