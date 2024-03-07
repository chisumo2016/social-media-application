<script setup>
import {computed, ref, watch} from 'vue'
import { XMarkIcon, CheckCircleIcon ,CameraIcon } from '@heroicons/vue/24/solid'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

import {usePage, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TablItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";





const coverImageSrc = ref('')
const avatarImageSrc = ref('')
const showNotification = ref(true)
const authUser = usePage().props.auth.user;

const imagesForm = useForm({
    avatar: null,
    cover: null,
})

const props = defineProps({
    errors:Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    success: {
        type: String,
    },
    isCurrentUserFollower: Boolean,
    followerCount: Number,
    user:{
        type: Object,
    },

});
///authUser && authUser.id === props.user.id     //console.log(authUser,  props.user)
const isMyProfile = computed(() => authUser && authUser.id === props.user.id )

// watch(props.status, (newValue, oldValue) =>{
//     console.log(newValue, oldValue)
//     if (!oldValue && newValue){
//         setInterval(() =>{
//             showNotification.value = false
//         }, 5000)
//     }
// })

function onCoverChange(event) {
    console.log(event)
    imagesForm.cover = event.target.files[0]
    if (imagesForm.cover){
        //read that file
        const  reader = new FileReader()
        reader.onload = () => {
            console.log("Onload")
            coverImageSrc.value = reader.result
        }
        reader.readAsDataURL(imagesForm.cover)
    }
}

function onAvatarChange(event) {
    console.log(event)
    imagesForm.avatar = event.target.files[0]
    if (imagesForm.avatar){
        //read that file
        const  reader = new FileReader()
        reader.onload = () => {
            console.log("Onload")
            avatarImageSrc.value = reader.result
        }
        reader.readAsDataURL(imagesForm.avatar)
    }
}

function cancelCoverImage() {
    imagesForm.cover = null;
    coverImageSrc.value =null
}

function cancelAvatarImage() {
    imagesForm.avatar = null;
    avatarImageSrc.value =null
}

function submitlCoverImage() {
    console.log(imagesForm.cover); //send to backend
    imagesForm.post(route('profile.updateImages'),{
        preserveScroll: true,
        onSuccess: (user) => {
            showNotification.value = true
            cancelCoverImage()
            setInterval(() =>{
                showNotification.value = false
            }, 3000)
        }
    })
}

function submitAvatarImage() {
    console.log(imagesForm.cover); //send to backend
    imagesForm.post(route('profile.updateImages'),{
        preserveScroll: true,
        onSuccess: (user) => {
            showNotification.value = true
            cancelAvatarImage()
            setInterval(() =>{
                showNotification.value = false
            }, 3000)
        }
    })
}

function followUser() {
    const form = useForm({
        follow: props.isCurrentUserFollower ? false : true  //!props.isCurrentUserFollower
    })

    form.post(route('user.follow', props.user.id), {
        preserveScroll: true
    })
}

</script>
<template>
    <AuthenticatedLayout>
        <div class="max-w-[768px]  mx-auto h-full overflow-auto">
            <div
                v-show="showNotification && success"
                class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white"
            >
                {{ success}}.
            </div>

            <div
                v-if="errors.cover"
                class="my-2 py-2 px-3 font-medium text-sm bg-red-400 text-white"
            >
                {{ errors.cover }}
            </div>

            <div class="group  relative bg-white">

                <div>
                    <img
                        :src="coverImageSrc || user.cover_url || '/image/default_cover.webp'" alt=""
                        class="w-full h-[200px] object-cover">
                    <div class="absolute top-2  right-2">
                        <button
                            v-if="!coverImageSrc"
                            class="bg-gray-50
                                   hover:bg-gray-100
                                   text-gray-800 py-1
                                   px-2 text-xs flex items-center opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="w-3 h-3 mr-2">
                                <path d="M12 9a3.75 3.75 0 1 0 0 7.5A3.75 3.75 0 0 0 12 9Z"/>
                                <path fill-rule="evenodd"
                                      d="M9.344 3.071a49.52 49.52 0 0 1 5.312 0c.967.052 1.83.585 2.332 1.39l.821 1.317c.24.383.645.643 1.11.71.386.054.77.113 1.152.177 1.432.239 2.429 1.493 2.429 2.909V18a3 3 0 0 1-3 3h-15a3 3 0 0 1-3-3V9.574c0-1.416.997-2.67 2.429-2.909.382-.064.766-.123 1.151-.178a1.56 1.56 0 0 0 1.11-.71l.822-1.315a2.942 2.942 0 0 1 2.332-1.39ZM6.75 12.75a5.25 5.25 0 1 1 10.5 0 5.25 5.25 0 0 1-10.5 0Zm12-1.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                      clip-rule="evenodd"/>
                            </svg>

                            Update Cover Image
                            <input
                                @change="onCoverChange"
                                type="file"
                                class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer">
                        </button>

                        <div v-else class="flex gap-2 bg-white p-2 opacity-0  group-hover:opacity-100">
                            <button
                                @click="cancelCoverImage"
                                class="bg-gray-50
                                   hover:bg-gray-100
                                   text-gray-800 py-1
                                   px-2 text-xs flex items-center">
                                <XMarkIcon class="h-3 w-3 mr-2"/>
                                Cancel

                            </button>
                            <button
                                @click="submitlCoverImage"
                                class="bg-gray-800
                                   hover:bg-gray-900
                                   text-gray-100 py-1
                                   px-2 text-xs flex  items-center">
                                <CheckCircleIcon class="h-3 w-3 mr-2"/>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>

              <!-- Avatar  -->
                <div class="flex">

                    <div class="flex items-center justify-center  relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full overflow-hidden">
                        <img
                            :src="avatarImageSrc || user.avatar_url || '/image/default_cover.webp'"
                            alt=""
                            class="w-full h-full object-cover rounded-full">

                            <button
                                v-if="!avatarImageSrc"
                                class="absolute
                                        left-0
                                        top-0 right-0 bottom-0
                                        bg-black/50 text-gray-200
                                        flex items-center justify-center
                                        text-xs opacity-0 group-hover/avatar:opacity-100 rounded-full">

                                <CameraIcon class="w-8 h-8"/>

                                <input
                                    @change="onAvatarChange"
                                    type="file"
                                    class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer">
                            </button>

                            <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
                                <button
                                    @click="cancelAvatarImage"
                                    class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                    <XMarkIcon class="h-5 w-5"/>

                                </button>
                                <button
                                    @click="submitAvatarImage"
                                    class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                    <CheckCircleIcon class="h-5 w-5"/>

                                </button>
                            </div>

                    </div>
                    <div class="flex justify-between items-center flex-1 p-4">
                        <div>
                            <h2 class="font-bold text-lg">{{ user.name }}</h2>
                            <p class="text-sm text-gray-700"> {{ followerCount }} followers</p>
                        </div>
                            <div>
                                <!-- Follow User Button  -->
                                <PrimaryButton
                                    v-if="!isCurrentUserFollower"
                                    @click="followUser">
                                    Follow User
                                </PrimaryButton>

                                <DangerButton
                                    v-else
                                    @click="followUser">
                                    Unfollow User
                                </DangerButton>
                            </div>
                    </div>
                </div>
            </div>
            <div class="border-t">
                <TabGroup>
                    <TabList class="flex bg-white">

                        <Tab v-slot="{ selected }" as="template">
                            <TablItem text="Posts" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TablItem text="Followers" :selected="selected"/>
                        </Tab>

                        <Tab v-slot="{ selected }" as="template">
                            <TablItem text="Followings" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TablItem text="Photos" :selected="selected"/>
                        </Tab>
                        <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
                            <TablItem text="My Profile" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">

                        <TabPanel  class="bg-white p-3 shadow">
                            Post Content
                        </TabPanel>

                        <TabPanel  class="bg-white p-3 shadow">
                            Followers
                        </TabPanel>
                        <TabPanel  class="bg-white p-3 shadow">
                            Photos
                        </TabPanel>
                        <TabPanel  v-if="isMyProfile">
                            <Edit :must-verify-email="mustVerifyEmail" :status="status"/>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
