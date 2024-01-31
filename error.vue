<!-- ATTACHMENT PREVIEW  -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 my-3">
<template v-for="(myFile, index) of attachmentFiles.slice(0,4)">

    <div  class="group aspect-square  bg-blue-100 flex flex-col items-center justify-center  text-gray-500 relative">

        <!--  Download Image    -->
        <button class="opacity-0 group-hover:opacity-100 transition-all   w-8 h-8 flex items-center justify-center text-gray-200 bg-gray-700 rounded absolute right-2 top-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 class="w-4 h-4  cursor-pointer">
                <path fill-rule="evenodd"
                      d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z"
                      clip-rule="evenodd"/>
            </svg>
        </button>
        <!--  Render max 3   -->
        <div v-if="index == 3" class="absolute
                                                            left-0 top-0
                                                            right-0 bottom-0
                                                            z-10 bg-black/60
                                                            text-white flex items-center justify-center text-2xl">
            + {{ attachmentFiles.length -4 }} more
        </div>

        <!--  Remove File Bitton    -->
        <button
            @click="removeFile(myFile)"
            class="absolute right-3 top-3 Z-20
                                                       w-7 h-7 flex
                                                       items-center
                                                       justify-center
                                                        bg-black/30 text-white rounded-full hover:bg-black/40">
            <XMarkIcon class="h-5 w-5"/>

        </button>

        <!--    Attachment   -->
        <img v-if="isImage(myFile.file)"
             :src="myFile.url" alt=""
             class="object-cover aspect-square">
        <template v-else>
            <PaperClipIcon class="w-10 h-10 mb-3"/>

            <small class="text-center">{{ myFile.file.name }}</small>
        </template>
    </div>
</template>
</div>
<script setup>
import {isImage} from "@/Helpers/helpers.js";
import {PaperClipIcon, XMarkIcon} from "@heroicons/vue/20/solid/index.js";
</script>
