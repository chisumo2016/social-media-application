<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import {PencilIcon, TrashIcon,  EllipsisVerticalIcon} from '@heroicons/vue/20/solid'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon,ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router} from "@inertiajs/vue3";
import {isImage} from "@/Helpers/helpers.js";
import {PaperClipIcon} from "@heroicons/vue/20/solid/index.js"; //'../../Helpers/helpers.js

const props = defineProps({
    post: Object
})

const emit = defineEmits(['editClick' ,'attachmentClick'])

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
        <Disclosure v-slot="{ open }">
<!--                <pre>{{ open}}</pre>-->
            <div v-if="!open " v-html="post.body" class="ck-content-output"/>

            <template v-if="post.body">
                <DisclosurePanel>
                    <div v-html="post.body" class="ck-content-output"/>
                </DisclosurePanel>
                <div class="flex justify-end">
                    <DisclosureButton  class="hover:underline text-blue-500">
                        {{ open ? 'Read Less' : 'Read More'}}
                    </DisclosureButton>
                </div>
            </template>
        </Disclosure>
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
                <a :href="route('post.download',attachment)" class="z-20 opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center text-gray-100 bg-gray-700 rounded absolute right-2 top-2 cursor-pointer hover:bg-gray-800">
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
    <div class="flex gap-2">
<!--            Like Icon-->
        <button class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 py-2 px-4 flex-1 rounded-lg">
            <HandThumbUpIcon class="w-5 h-5 mr-2"/>
                Like
        </button>
        <button class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 py-2 px-4 flex-1 rounded-lg">
            <ChatBubbleLeftRightIcon  class="w-5 h-5 mr-2"/>
            Comment
        </button>
    </div>
</div>
</template>

<style scoped>

</style>
