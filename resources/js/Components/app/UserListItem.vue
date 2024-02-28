<script setup>
import {Link, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    user:Object,
    forApprove:{
        type:Boolean,
        default:false
    }
});

defineEmits(['approve','reject'])
</script>

<template>
    <div class="mb-3 cursor-pointer bg-white transition-all  border-2 border-transparent hover:border-indigo-500">
        <Link  :href="route('profile', user.username)" class="flex items-center gap-2 py-2 px-2">
            <img :src="user.avatar_url || '/image/default_cover.webp'" alt="" class="w-[32px] rounded-full">
            <div class="flex justify-between flex-1">
                <h3 class="font-black">{{ user.name }}</h3>

                <!--   APPROVE / REJECT BUTTON   -->
                <div class="flex gap-1">
                    <button
                        class="text-xs py-1 px-2 rounded bg-emerald-500 hover:bg-emerald-600 text-white"
                        v-if="forApprove"
                        @click.prevent.stop="$emit('approve',user)">
                        Approve
                    </button>

                    <button
                        class="text-xs py-1 px-2 rounded bg-red-500 hover:bg-emerald-600 text-white"
                        v-if="forApprove"
                        @click.prevent.stop="$emit('reject',user)">
                        Reject
                    </button>
                </div>
            </div>
        </Link>
    </div>
</template>

<style scoped>

</style>
