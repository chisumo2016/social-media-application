
<script setup>
import {computed, ref} from 'vue'
import { XMarkIcon, CheckCircleIcon ,CameraIcon } from '@heroicons/vue/24/solid'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

import {usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TablItem from "@/Pages/Profile/Partials/TabItem.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from '@inertiajs/vue3'
import InviteUserModal from "@/Components/app/InviteUserModal.vue";
import UserListItem from "@/Components/app/UserListItem.vue";
import TextInput from "@/Components/TextInput.vue";
import GroupForm from "@/Components/app/GroupForm.vue";
import PostList from "@/Components/app/PostList.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import TabPhotos from "@/Pages/Profile/TabPhotos.vue";




const coverImageSrc = ref('')
const thumbnailImageSrc = ref('')
const showNotification = ref(true)
const showInviteUserModal = ref(false)
const authUser = usePage().props.auth.user;
const  searchKeyword = ref('')

const isCurrentUserAdmin = computed(() => props.group.role === 'admin' )
const isJoinedToGroup = computed(() =>  props.group.role && props.group.status === 'approved')

const props = defineProps({
    errors:Object,

    success: {
        type: String,
    },
    group:{
        type: Object,
    },
    posts: Object, //data&pagination
    users: Array,
    requests: Array,
    photos:Array,

});

const aboutForm = useForm({
    name: usePage(). props.group.name,
    auto_approval: !!parseInt(usePage().props.group.auto_approval) ,
    about : usePage().props.group.about
})

const imagesForm = useForm({
    thumbnail: null,
    cover: null,
})



function onCoverChange(event) {
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

function onThumbnailChange(event) {
    imagesForm.thumbnail = event.target.files[0]
    if (imagesForm.thumbnail){
        //read that file
        const  reader = new FileReader()
        reader.onload = () => {
            console.log("Onload")
            thumbnailImageSrc.value = reader.result
        }
        reader.readAsDataURL(imagesForm.thumbnail)
    }
}

function cancelCoverImage() {  //reset
    imagesForm.cover = null;
    coverImageSrc.value =null
}

function cancelThumbnailImage() {
    imagesForm.thumbnail = null;
    thumbnailImageSrc.value =null
}

function submitlCoverImage() {
    console.log(imagesForm.cover); //send to backend
    imagesForm.post(route('group.updateImages',props.group.slug),{
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true
            cancelCoverImage()
            setInterval(() =>{
                showNotification.value = false
            }, 3000)
        }
    })
}

function submitThumbnailImage() {
    console.log(imagesForm.cover); //send to backend
    imagesForm.post(route('group.updateImages', props.group.slug),{
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true
            cancelThumbnailImage()
            setInterval(() =>{
                showNotification.value = false
            }, 3000)
        }
    })
}

function joinToGroup() {
    const  form = useForm({})

    form.post(route('group.join', props.group.slug), {
        preserveScroll: true
    })
}

function approveUser(user) {
    const  form = useForm({
        user_id: user.id,
        action : 'approve'
    })
    form.post(route('group.approveRequest', props.group.slug), {
        preserveScroll: true,
    })
}

function rejectUser() {
    const  form = useForm({
        user_id: user.id,
        action:'reject',
    })
    form.post(route('group.approveRequest', props.group.slug), {
        preserveScroll: true
    })
}

function onRoleChange(user, role) {
    console.log(user, role)
    const  form = useForm({
        user_id: user.id,
        role
    })
    form.post(route('group.changeRole', props.group.slug), {
        preserveScroll: true,
    })
}

function updateGroup() {
    aboutForm.put(route('group.update', props.group.slug), {
        preserveScroll: true,
    })
}

function deleteUser(user) {
    if (!window.confirm(`Are you sure you want to remove user "${user.name}" from this group`)){
        return false;
    }

    const  form = useForm({
        user_id: user.id,

    })
    form.delete(route('group.removeUser', props.group.slug), {
        preserveScroll: true
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-[768px]  mx-auto h-full overflow-auto">
            <div class="px-4 pt-0">
<!--                <pre>{{ posts}}</pre>-->
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
            </div>

            <div class="p-4">
                <div class="group  relative bg-white">

                        <img
                            :src="coverImageSrc || group.cover_url || '/image/default_cover.webp'" alt=""
                            class="w-full h-[200px] object-cover">

                        <div v-if="isCurrentUserAdmin" class="absolute top-2  right-2">
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

                    <!-- Avatar  -->
                    <div class="flex">

                        <div class="flex items-center justify-center  relative group/thumbnail -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                            <img
                                :src="thumbnailImageSrc || group.thumbnail_url || '/image/default_cover.webp'"
                                alt=""
                                class="w-full h-full object-cover rounded-full">

                            <button
                                v-if="isCurrentUserAdmin && !thumbnailImageSrc"
                                class="absolute
                                        left-0
                                        top-0 right-0 bottom-0
                                        bg-black/50 text-gray-200
                                        flex items-center justify-center
                                        text-xs opacity-0 group-hover/thumbnail:opacity-100 rounded-full">

                                <CameraIcon class="w-8 h-8"/>

                                <input
                                    @change="onThumbnailChange"
                                    type="file"
                                    class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer">
                            </button>

                            <div v-else-if="isCurrentUserAdmin" class="absolute top-1 right-0 flex flex-col gap-2">
                                <button
                                    @click="cancelThumbnailImage"
                                    class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                    <XMarkIcon class="h-5 w-5"/>

                                </button>
                                <button
                                    @click="submitThumbnailImage"
                                    class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                    <CheckCircleIcon class="h-5 w-5"/>

                                </button>
                            </div>

                        </div>

                        <div class="flex justify-between items-center flex-1 p-4">
                            <h2 class="font-bold text-lg">{{ group.name }}</h2>

                            <!--  User not  authenticated -->
                            <PrimaryButton v-if="!authUser" :href="route('login')">
                                Login to join this group
                            </PrimaryButton>

                            <!--  Invite User Button -->
                            <PrimaryButton
                                v-if="isCurrentUserAdmin"
                                @click="showInviteUserModal = true">

                                Invite Users
                            </PrimaryButton>

                            <!--  Join To Group -->
                            <PrimaryButton
                                @click="joinToGroup"
                                v-if="authUser && !group.role && group.auto_approval">
                                Join  to Group
                            </PrimaryButton>

                            <!--  Request to Join  -->
                            <PrimaryButton
                                @click="joinToGroup"
                                v-if="authUser && !group.role && !group.auto_approval">
                                Request to Join
                            </PrimaryButton>

                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t p-4 pt-0">
                <TabGroup>
                    <TabList class="flex bg-white">

                        <Tab v-slot="{ selected }" as="template">
                            <TablItem text="Posts" :selected="selected"/>
                        </Tab>
                        <Tab v-if="isJoinedToGroup" v-slot="{ selected }" as="template">
                            <TablItem text="Users" :selected="selected"/>
                        </Tab>

                        <Tab v-if="isCurrentUserAdmin" v-slot="{ selected }" as="template">
                            <TablItem text="Pending Requests" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TablItem text="Photos" :selected="selected"/>
                        </Tab>

                        <Tab  v-slot="{ selected }" as="template">
                            <TablItem text="About" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">

                        <TabPanel>

                            <template v-if="posts">
                                <CreatePost :group="group"/>
                                <PostList v-if="posts.data.length" :posts="posts.data" class="flex-1"/>

                                <div v-else class="py-8 text-center">
                                    There are no posts iin  this group . Be the first and create it.
                                </div>
                            </template>

                            <div v-else class="py-8 text-center">
                                You don't have permission to view  these posts.
                            </div>

                        </TabPanel>

                        <TabPanel v-if="isJoinedToGroup" class="">
                            <!-- Search Input   -->
                           <div class="mb-3">
                               <TextInput :model-value="searchKeyword" placeholder="Type to search group" class="w-full"/>
                           </div>
                            <div class="grid grid-cols-2 gap-3">
                                <UserListItem v-for="user of users"
                                              :user="user"
                                              :key="user.id"
                                              :show-role-dropdown="isCurrentUserAdmin"
                                              @role-change="onRoleChange"
                                              @delete="deleteUser"
                                              :disable-role-dropdown="group.user_id === user.id"
                                              class="shadow rounded-lg" />
                            </div>
                            <UserListItem v-for="user of users" :user="user" :key="user.id"/>
                        </TabPanel>
                        <TabPanel v-if="isCurrentUserAdmin" class="">
                            <div v-if="requests.length" class="grid grid-cols-2 gap-3">
                                <UserListItem v-for="user of requests"
                                              :user="user"
                                              :key="user.id"
                                              class="shadow rounded-lg"
                                              @approve="approveUser"
                                              @reject="rejectUser"
                                              :for-approve="true"/>
                            </div>
                            <div class="py-8 text-center">
                                    There are  no pending  request
                            </div>
                        </TabPanel>
                        <TabPanel class="bg-white p-3 shadow">
                            <TabPhotos :photos="photos" />
                        </TabPanel>
<!--                        <pre>{{ group}}</pre>-->
                        <TabPanel class="bg-white p-3 shadow">
                            <template v-if="isCurrentUserAdmin">
                                <GroupForm :form="aboutForm"/>
                                <PrimaryButton @click="updateGroup">
                                    Submit
                                </PrimaryButton>
                            </template>
                            <div v-else class="ck-content-output" v-html="group.about">

                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>

    <InviteUserModal v-model="showInviteUserModal"/>
</template>


<style scoped>

</style>
