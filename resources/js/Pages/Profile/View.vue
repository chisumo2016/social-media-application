<template>
    <AuthenticatedLayout>
        <div class="w-[768px]  mx-auto h-full overflow-auto">
            <div class="relative bg-white">
                <img
                    src="https://www.prodraw.net/fb_cover/images/fb_cover_65.jpg" alt=""
                    class="w-full h-[200px] object-cover">

                <div class="flex">

                    <img
                        src="https://cdn.iconscout.com/icon/free/png-256/free-avatar-370-456322.png?f=webp"
                        alt=""
                        class="ml-[48px] w-[128px] h-[128px] -mt-[64px]">
                    <div class="flex justify-between items-center flex-1 p-4">
                        <h2 class="font-bold text-lg">{{ user.name }}</h2>

                            <PrimaryButton v-if="authUser">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="w-4 h-4 mr-2">
                                    <path
                                        d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z"/>
                                </svg>

                                Edit Profile
                            </PrimaryButton>
                    </div>
                </div>
            </div>
            <div class="border-t">
                <TabGroup>
                    <TabList class="flex bg-white">
                        <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
                          <TablItem text="About" :selected="selected"/>
                        </Tab>
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
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel  v-if="isMyProfile">
                                <Edit :must-verify-email="mustVerifyEmail" :status="status"/>
                        </TabPanel>
                        <TabPanel  class="bg-white p-3 shadow">
                            Post Content
                        </TabPanel>

                        <TabPanel  class="bg-white p-3 shadow">
                            Followers
                        </TabPanel>
                        <TabPanel  class="bg-white p-3 shadow">
                            Photos
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed, ref} from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

import {usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TablItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const authUser = usePage().props.auth.user;

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user:{
       type: Object,
    }
});
  ///authUser && authUser.id === props.user.id     //console.log(authUser,  props.user)
const isMyProfile = computed(() => authUser && authUser.id === props.user.id )


</script>

<style scoped>

</style>
