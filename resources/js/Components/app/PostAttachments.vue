
<script setup>
import { ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import {isImage, isVideo} from "@/Helpers/helpers.js"; //'../../Helpers/helpers.js
import { PaperClipIcon } from  "@heroicons/vue/24/solid/index.js"


defineProps({
    attachments: Array
})

defineEmits(['attachmentClick'])
</script>

<template>
  <template v-for="(attachment,index) of attachments.slice(0,4)">

    <!--  Preview Attachment -->
    <div
        @click="$emit('attachmentClick', index)"
        class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">

      <!--  Render max 3   -->
      <div v-if="index === 3 && attachments.length > 4"
           class="absolute
                    left-0 top-0
                    right-0 bottom-0
                    z-10 bg-black/60
                    text-white flex items-center justify-center text-2xl">
        + {{ attachments.length -4 }} more
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

        <!--    Video   -->
        <div v-else-if="isVideo(attachment)" class="relative  flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                 class="absolute w-16 h-16 z-20  text-gray-800 text-white opacity-70">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
            </svg>

            <div class="absolute left-0 top-0 w-full h-full bg-black/50  z-10"></div>
            <video :src="attachment.url"></video>
        </div>

      <template v-else class="flex flex-col justify-center items-center">
        <PaperClipIcon class="w-4 h-4 mr-2"/>

        <small>{{ attachment.name }}</small>
      </template>
    </div>
  </template>
</template>

