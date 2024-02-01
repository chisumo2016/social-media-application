<script setup>
import { XMarkIcon ,PaperClipIcon ,BookmarkIcon } from '@heroicons/vue/20/solid'
import { useForm } from '@inertiajs/vue3'
import {computed, onMounted, onUpdated, reactive, ref, watch} from 'vue'

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'

import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {isImage} from "@/Helpers/helpers.js";


const editor = ClassicEditor;
const editorConfig ={
    toolbar: ['heading' ,'|', 'bold',
        'italic',
        'link',
        '|',
        'bulletedList',
        'numberedList',
        '|',
        'indent',
        'outdent',
        '|',
        'blockQuote',
    ],
    // balloonToolbar: ['balloonLink','balloonBlockquote','balloonIndent','balloonAlign','balloonImage']
}



const props = defineProps({
    post:{
        type: Object,
        required: true
    },
    modelValue: Boolean,
})

//const reactivePost  = reactive(props.post)
//console.log(props)

const form  = useForm({
    id: null,
    body: '',

})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

const emit = defineEmits(['update:modelValue'])

/**
 * {
 *     file: File,
 *     url: '',
 * }
 * @type {Ref<UnwrapRef<*[]>>}
 */
const attachmentFiles = ref([])

watch(() => props.post,() => {
    //console.log("Changed" , props.post)
    form.id = props.post.id
    form.body = props.post.body
})

function closeModal() {
    show.value = false
    form.reset()
    attachmentFiles.value = []
    //emit('update:modelValue', false) //emit to the parent via setter
}

function submit() {
    // const form = useForm({
    //     id: props.post.id,
    //     body:props.post.body,
    // })
    if (form.id){
        form.put(route('post.update', props.post.id),{
            preserveScroll: true,
            onSuccess: () =>{
                show.value = false
                form.reset()
            }
        })
    }else {
        // Create
        form.post(route('post.create'),{
            preserveScroll: true,
            onSuccess : () =>{
                show.value = false
                form.reset()
            }
        })
    }
}

async function onAttachmentChoose($event) {
   console.log($event.target.files)
    for (const  file of  $event.target.files){
        const myFile = {
            file,
            url : await readFile(file)
        }

           attachmentFiles.value.push(myFile)
    }
    //remove the old file fromm input
    $event.target.value = null;
    console.log(attachmentFiles.value)
}

async  function readFile(file) {
    return new  Promise((res, rej) =>{
        if (isImage(file)){ //(isImage({ mime: file.type}))
            const reader  = new FileReader();
            reader.onload = () =>{
                res(reader.result)
            }
            reader.onerror = rej
            reader.readAsDataURL(file)
        }else {
            res(null)
        }
    })
}

function removeFile(myFile) {
    attachmentFiles.value = attachmentFiles.value.filter(f => f != myFile)

}

</script>
<template>
   <teleport to="body">

       <TransitionRoot appear :show="show" as="template">
           <Dialog as="div" @close="closeModal" class="relative z-10">
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
                       class="flex min-h-full items-center justify-center p-4 text-center"
                   >
                       <TransitionChild
                           as="template"
                           enter="duration-300 ease-out"
                           enter-from="opacity-0 scale-95"
                           enter-to="opacity-100 scale-100"
                           leave="duration-200 ease-in"
                           leave-from="opacity-100 scale-100"
                           leave-to="opacity-0 scale-95"
                       >
                           <DialogPanel
                               class="w-full max-w-md transform overflow-hidden rounded bg-white text-left align-middle shadow-xl transition-all"
                           >
                               <DialogTitle
                                   as="h3"
                                   class="flex items-center justify-between py-3 px-4  font-medium  bg-gray-100 leading-6 text-gray-900"
                               >
                                   {{ form.id ? 'Update Post' : 'Create  Post' }}
                                   <button   @click="show = false" class="w-8 h-8 rounded-full hover:bg-black/5 transition flex items-center justify-center">

                                       <XMarkIcon class="w-4 h-4"/>
                                   </button>
                               </DialogTitle>

                               <div class="p-4">
                                <!--  Content       -->
                                   <PostUserHeader :post="post" :show-time="false" class="mb-4"/>
                                <!-- CKEDITOR  -->
                                   <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>

                                   <!-- ATTACHMENT PREVIEW  -->
                                   <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 my-3">
                                       <template v-for="(myFile, index) of attachmentFiles">

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

                               </div>

                               <div class="flex gap-2 py-3 px-4">
                                   <button
                                       type="button"
                                       class="flex items-center justify-center
                                            rounded-md
                                            bg-indigo-600
                                            px-3 py-2 text-sm
                                            font-semibold text-white
                                            shadow-sm hover:bg-indigo-500
                                            focus-visible:outline
                                            focus-visible:outline-2
                                            focus-visible:outline-offset-2
                                            focus-visible:outline-indigo-600 w-full relative"
                                       @click="submit"
                                   >
                                       <PaperClipIcon  class="w-4 h-4 mr-2"/>
                                       Attach Files
                                       <input
                                           @click.stop
                                           @change="onAttachmentChoose"
                                           type="file"
                                           multiple
                                           class="absolute left-0 top-0 right-0 bottom-0 opacity-0">
                                   </button>

                                   <button
                                       type="button"
                                       class="flex items-center justify-center
                                            rounded-md
                                            bg-indigo-600
                                            px-3 py-2 text-sm
                                            font-semibold text-white
                                            shadow-sm hover:bg-indigo-500
                                            focus-visible:outline
                                            focus-visible:outline-2
                                            focus-visible:outline-offset-2
                                            focus-visible:outline-indigo-600 w-full"
                                       @click="submit"
                                   >
                                       <BookmarkIcon class="w-4 h-4 mr-2"/>
                                       Submit
                                   </button>
                               </div>
                           </DialogPanel>
                       </TransitionChild>
                   </div>
               </div>
           </Dialog>
       </TransitionRoot>
   </teleport>
</template>

