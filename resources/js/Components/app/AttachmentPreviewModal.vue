<script setup>
import { XMarkIcon ,PaperClipIcon,ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/20/solid'

import {computed} from 'vue'

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import {isImage, isVideo} from "@/Helpers/helpers.js";



const props = defineProps({
    attachments:{
        type: Array,
        required: true
    },
    index:Number,
    modelValue: Boolean,
})


const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

const currentIndex = computed({
    get: () => props.index,
    set: (value) => emit('update:index', value)
})

const attachment = computed(() =>{
    //return props.attachments[props.index]
    return props.attachments[currentIndex.value]
})


const emit = defineEmits(['update:modelValue', 'update:index','hide'])



function closeModal() {
    show.value = false
    emit('hide')


    //emit('update:modelValue', false) //emit to the parent via setter
}

function prev() {
    if (currentIndex.value == 0) return
    currentIndex.value--;
}

function next() {
    if (currentIndex.value ==  props.attachments.length) return
    currentIndex.value++;
}




</script>
<template>
   <teleport to="body">

       <TransitionRoot appear :show="show" as="template">
           <Dialog as="div" @close="closeModal" class="relative z-50">
               <TransitionChild
                   as="template"
                   enter="duration-300 ease-out"
                   enter-from="opacity-0"
                   enter-to="opacity-100"
                   leave="duration-200 ease-in"
                   leave-from="opacity-100"
                   leave-to="opacity-0"
               >
                   <div class="fixed inset-0 bg-black/25" />
               </TransitionChild>

               <div class="fixed inset-0 overflow-y-auto">
                   <div
                       class="h-screen w-screen"
                   >
                       <TransitionChild
                           class="w-full h-full"
                           as="template"
                           enter="duration-300 ease-out"
                           enter-from="opacity-0 scale-95"
                           enter-to="opacity-100 scale-100"
                           leave="duration-200 ease-in"
                           leave-from="opacity-100 scale-100"
                           leave-to="opacity-0 scale-95"
                       >
                           <DialogPanel
                               class="flex flex-col w-full transform overflow-hidden  bg-slate-800 dark:bg-slate-900 text-left align-middle shadow-xl transition-all"
                           >

                               <button
                                   @click="closeModal"
                                   class="absolute
                                   right-3 top-3 z-30
                                   w-1-0 h-10 rounded-full
                                   hover:bg-black/10
                                   dark:hover:bg-black/30
                                   transition flex
                                   items-center
                                   justify-center text-gray-100 z-40">

                                   <XMarkIcon class="w-6 h-6"/>
                               </button>

                               <!--    Attachment  Preview  -->
                               <div class="relative group h-full">
                                   <div
                                        @click="prev"
                                        class="absolute
                                        flex items-center
                                         w-12 h-full left-0
                                         bg-black/5
                                         group-hover:opacity-100
                                         text-gray-100 cursor-pointer z-30">
                                       <ChevronLeftIcon  class="w-12"/>
                                   </div>
                                   <div @click="next"
                                        class="absolute flex
                                                opacity-0
                                                items-center w-12
                                                right-0 h-full
                                                bg-black/5
                                                group-hover:opacity-100 text-gray-100  cursor-pointer z-30">
                                       <ChevronRightIcon class="w-12"/>
                                   </div>

                                   <!--    Attachments  -->
                                   <div class="flex items-center justify-center w-full h-full p-3">
                                       <img v-if="isImage(attachment)"
                                            :src="attachment.url" alt=""
                                            class="max-w-full max-h-full">

                                       <!--    Video   -->
                                       <div v-else-if="isVideo(attachment)" class="flex items-center">

                                           <video :src="attachment.url" controls autoplay></video>
                                       </div>

                                       <div v-else class="p-32 flex flex-col justify-center items-center text-gray-100" >

                                           <PaperClipIcon class="w-10 h-10 mb-3"/>
                                           <small class="text-center">{{ attachment }}</small>
                                       </div>
                                   </div>
                               </div>
                           </DialogPanel>
                       </TransitionChild>
                   </div>
               </div>
           </Dialog>
       </TransitionRoot>
   </teleport>
</template>


