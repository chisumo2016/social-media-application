<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import {PencilIcon, TrashIcon,  EllipsisVerticalIcon} from '@heroicons/vue/20/solid'

import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    post: Object
})

const emit = defineEmits(['editClick'])

function isImage(attachment) {
    const  mime = attachment.mime.split('/')
    return mime[0].toLowerCase() ==  'image'
}

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
</script>

<template>
<!--    Post Header-->
<div class="bg-white border rounded p-4 mb-3">
    <div class="flex items-center justify-between  mb-3">

        <PostUserHeader :post="post"/>

        <!--    Drop down    -->
       <Menu as="div" class="relative inline-block text-left">
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
                            class="absolute right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
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
            <div v-if="!open " v-html="post.body.substring(0, 200)"/>

            <template v-if="post.body.length > 200">
                <DisclosurePanel>
                    <div v-html="post.body"/>
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
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
        <template v-for="attachment of post.attachments">

            <div  class="group aspect-square  bg-blue-100 flex flex-col items-center justify-center  text-gray-500 relative">
            <!--     Download Image            -->
                <button class="opacity-0 group-hover:opacity-100 transition-all   w-8 h-8 flex items-center justify-center text-gray-200 bg-gray-700 rounded absolute right-2 top-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="w-4 h-4  cursor-pointer">
                        <path fill-rule="evenodd"
                              d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>

                <!--    Attachment            -->
                <img v-if="isImage(attachment)"
                     :src="attachment.url" alt=""
                     class="object-cover aspect-square">
                <template v-else>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="currentColor"
                         class="w-12 h-12">
                        <path
                            d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z"/>
                        <path
                            d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z"/>
                    </svg>

                    <small>{{ attachment.name }}</small>
                </template>
            </div>
        </template>
    </div>
    <div class="flex gap-2">
<!--            Like Icon-->
        <button class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 py-2 px-4 flex-1 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z" />
            </svg>
                Like
        </button>
        <button class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 py-2 px-4 flex-1 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 0 0 6 21.75a6.721 6.721 0 0 0 3.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 0 1-.814 1.686.75.75 0 0 0 .44 1.223ZM8.25 10.875a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM10.875 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875-1.125a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25Z" clip-rule="evenodd" />
            </svg>
            Comment
        </button>
    </div>
</div>
</template>

<style scoped>

</style>
