<script setup>
import { ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import { PaperClipIcon } from  "@heroicons/vue/24/solid/index.js"
import {isImage} from "@/Helpers/helpers.js";
import {ref} from "vue";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";

defineProps({
    photos: Array
})

const showModal = ref(false)
const currentPhotoIndex = ref(0)

function openPhoto(index) { //current file I am clicking
    //debugger;
    currentPhotoIndex.value = index
    showModal.value = true;
}

</script>

<template>
   <div class="grid gap-2 grid-cols-2 sm:grid-cols-3">
       <template v-for="(attachment,index) of photos">

           <!--  Preview Attachment -->
           <div
               @click="openPhoto(index)"
               class="group aspect-square
                   bg-blue-100 flex flex-col
                   items-center justify-center
                   text-gray-500 relative cursor-pointer">


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
                   <PaperClipIcon class="w-4 h-4 mr-2"/>

                   <small>{{ attachment.name }}</small>
               </template>
           </div>
       </template>
   </div>
    <div v-if="!photos.length" class="py-8 text-center text-gray-600">
        There are no photos
    </div>


       <!--    Preview Attachment Modal-->
       <AttachmentPreviewModal
           :attachments="photos || []"
           v-model="showModal"
           v-model:index="currentPhotoIndex"/>
</template>

<style scoped>

</style>
