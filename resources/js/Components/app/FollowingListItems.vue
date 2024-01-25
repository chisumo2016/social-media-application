<script setup>

import TextInput from "@/Components/TextInput.vue";
import FollowingItem from "@/Components/app/FollowingItem.vue";
import {ref} from "vue";

const searchKeyword = ref();
</script>

<template>
    <TextInput :model-value="searchKeyword" placeholder="Type to search group" class="w-full mt-3"/>

    <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
        <div v-if="false" class="text-gray-400 text-center p-3">
            You don't friends yet
        </div>
        <div v-else>
            <FollowingItem image="https://picsum.photos/100"
                           title="Jon Doe"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <FollowingItem image="https://picsum.photos/100"
                           title="Bernard Chisumo"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <FollowingItem image="https://picsum.photos/100"
                           title="Elon mass"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>
            <FollowingItem image="https://picsum.photos/100"
                           title="Jon Doe"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <FollowingItem image="https://picsum.photos/100"
                           title="Bernard Chisumo"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <FollowingItem image="https://picsum.photos/100"
                           title="Elon mass"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>
            <FollowingItem image="https://picsum.photos/100"
                           title="Jon Doe"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <FollowingItem image="https://picsum.photos/100"
                           title="Bernard Chisumo"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <FollowingItem image="https://picsum.photos/100"
                           title="Elon mass"
                           description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>
        </div>
    </div>
</template>

<style scoped>

</style>
