<script setup>
import { XMarkIcon ,PaperClipIcon ,BookmarkIcon ,ArrowUpLeftIcon} from '@heroicons/vue/20/solid'
import {useForm, usePage} from '@inertiajs/vue3'
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

/**
 * {
 *     file: File,
 *     url: '',
 * }
 * @type {Ref<UnwrapRef<*[]>>}
 */
const attachmentFiles = ref([])
const attachmentErrors = ref([])
const FormErrors  = ref({})
//const showExtensionsText = ref(false)

const props = defineProps({
    post:{
        type: Object,
        required: true
    },
    modelValue: Boolean,
})

const attachmentExtensions = usePage().props.attachmentExtensions
//const pageProps = usePage().props;
//console.log(pageProps)


//const reactivePost  = reactive(props.post)
//console.log(props)

const form  = useForm({
    // id: null,
    body: '',
    attachments:[], //send to  the server
    deleted_file_ids:[],
    _method: 'POST'

})

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})

const computedAttachments = computed(() => {
    return [...attachmentFiles.value, ...(props.post.attachments || [])];  //merge old . new
})

const showExtensionsText = computed(() =>{
    for (let myFile of attachmentFiles.value){
        const file = myFile.file
        let parts = file.name.split('.')
        let  ext = parts.pop().toLowerCase()
        console.log(ext)
        if (!attachmentExtensions.includes(ext)){
            return true
        }
    }

    return  false
})


const emit = defineEmits(['update:modelValue','hide'])



watch(() => props.post,() => {
    console.log("This is triggered" , props.post)
        form.body = props.post.body || ''   //form.id = props.post.id // have this inside the url
})

function closeModal() {
    show.value = false
    emit('hide')
    resetModal()

    //emit('update:modelValue', false) //emit to the parent via setter
}

function submit() {
    form.attachments = attachmentFiles.value.map(myFile  => myFile.file)

    /*Update Post*/
    if (props.post.id){
        form._method = 'PUT'
        form.post(route('post.update', props.post.id),{
            preserveScroll: true,
            onSuccess: (resp) =>{
                console.log("on success", resp)
                closeModal()
                //show.value = false
                //resetModal()
                //form.reset()
            },
            onError: (errors) =>{
                //iterate
                processErrors(errors)
                console.log(errors)
            }
        })
    }else {
       /*Create Post*/
        form.post(route('post.create'),{
            preserveScroll: true,
            onSuccess : (resp) => {
                console.log("on success", resp)
                closeModal()
                //show.value = false
                //form.reset()
            },
            onError: (errors) =>{
                //iterate
                processErrors(errors)
                console.log(errors)
            }
        })
    }
}

async function onAttachmentChoose($event) {
    //showExtensionsText.value = false
   console.log($event.target.files)
    for (const  file of  $event.target.files){

        const myFile = { //object
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
    if (myFile.file){
        attachmentFiles.value = attachmentFiles.value.filter(f => f != myFile)
    }else{
        form.deleted_file_ids.push(myFile.id)
        myFile.deleted = true
    }

}

function resetModal() {
    form.reset()
    FormErrors.value ={}
    attachmentFiles.value = []
    showExtensionsText.value= false
    attachmentErrors.value = []
    if (props.post.attachments){
        props.post.attachments.forEach(file => file.deleted = false)
    }

}

function undoDelete(myFile) {
    myFile.deleted = false;
    form.deleted_file_ids = form.deleted_file_ids.filter(id => myFile.id !== id)

}

function processErrors(errors)
{
    FormErrors.value =  errors
    for (const key in errors){
        if (key.includes('.')){ //error for attachments
            const[,index] = key.split('.')
            attachmentErrors.value[index] = errors[key]
        }else{

        }
    }
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
                               class="w-full max-w-md transform overflow-hidden rounded bg-white dark:bg-slate-900 text-left align-middle shadow-xl transition-all"
                           >
                               <DialogTitle
                                   as="h3"
                                   class="flex items-center justify-between py-3 px-4 font-medium bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-gray-100"
                               >
                                   {{ post.id ? 'Update Post' : 'Create  Post' }}

                                   <button   @click="closeModal" class="w-8 h-8 rounded-full hover:bg-black/5 dark:hover:bg-black/30 transition flex items-center justify-center">

                                       <XMarkIcon class="w-4 h-4"/>
                                   </button>
                               </DialogTitle>

                               <div class="p-4">
                                <!--  Content       -->
                                   <PostUserHeader :post="post" :show-time="false" class="mb-4"/>

                                <!-- CKEDITOR  -->
                                   <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>

                                   <!-- EXTENSIONS -->
                                   <div v-if="showExtensionsText"
                                        class="border-l-4 border-amber-500 py-2 px-3 bg-amber-100 mt-3 text-gray-800">
                                       File must be one of the  following extensions:
                                       <small>{{ attachmentExtensions.join(', ') }}</small>
                                   </div>

                                   <div v-if="FormErrors.attachments" class="border-l-4 border-red-500 py-2 px-3 bg-amber-100 mt-3 text-gray-800">
                                        {{ FormErrors.attachments }}
                                   </div>

                                   <!-- ATTACHMENT PREVIEW - EDIT POST -->

                                    <!--      <pre>{{ form.deleted_file_ids }}</pre>-->
                                   <div class="grid  gap-3 my-3" :class="[computedAttachments  == 1 ? 'grid-cols-1' : 'grid-cols-2']">

                                       <div v-for="(myFile, index ) of computedAttachments">

                                           <div  class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                                                 :class="attachmentErrors[ind] ? 'border-red-500' : '' ">

                                               <!--  Download Image    -->
                                               <button class="opacity-0 group-hover:opacity-100 transition-all
                                                        w-8 h-8 flex items-center justify-center text-gray-200 bg-gray-700 rounded absolute right-2 top-2">
                                                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                        class="w-4 h-4  cursor-pointer">
                                                       <path fill-rule="evenodd"
                                                             d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z"
                                                             clip-rule="evenodd"/>
                                                   </svg>
                                               </button>
                                               <!--  File will be marked as deleted    -->
                                                <div v-if="myFile.deleted" class="absolute  z-10 left-0 bottom-0 right-0 py-2 px-3 bg-black text-sm text-white flex justify-between items-center">

                                                    To be deleted
                                                    <ArrowUpLeftIcon
                                                        @click="undoDelete(myFile)"
                                                        class="w-4 h-4 cursor-pointer"/>
                                                </div>
                                               <!--  Remove File Bitton    -->
                                               <button
                                                   @click="removeFile(myFile)"
                                                   class="absolute z-20 right-3 top-3
                                                                w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40">
                                                   <XMarkIcon class="h-5 w-5"/>

                                               </button>

                                               <!--    Attachment  Preview  -->
                                               <img v-if="isImage(myFile.file ||  myFile)"
                                                    :src="myFile.url" alt=""
                                                    class="object-contain aspect-square" :class="myFile.deleted ? 'opacity-50' : ''">
                                               <div v-else class="flex flex-col justify-center items-center px-3" :class="myFile.deleted ? 'opacity-50' : ''">
                                                   <PaperClipIcon class="w-10 h-10 mb-3"/>

                                                   <small class="text-center">{{ (myFile.file || myFile).name }}</small>
                                               </div>
                                           </div>
                                             <small class="text-red-500"> {{ attachmentErrors[ind] }}</small>
                                       </div>
                                   </div>
<!--                                        <prev>{{ post.attachments }}</prev>-->
                               </div>

                               <div class="flex gap-2 py-3 px-4">
                                   <button
                                       type="button"

                                       class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full relative"
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
                                       class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
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


