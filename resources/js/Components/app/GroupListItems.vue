<script setup>

import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/app/GroupItem.vue";
import {ref} from "vue";

const searchKeyword = ref();
</script>

<template>
    <TextInput :model-value="searchKeyword" placeholder="Type to search group" class="w-full mt-4"/>
    <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
        <div v-if="false" class="text-gray-400 text-center p-3">
            You are not joined to any groups
        </div>
        <div v-else>
            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developer"
                       description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <GroupItem image="https://picsum.photos/100"
                       title="Vuejs Developer"
                       description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developer"
                       description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>
            <GroupItem image="https://picsum.photos/100"
                       title="Vuejs Developer"
                       description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>

            <GroupItem image="https://picsum.photos/100"
                       title="Laravel Developer"
                       description="Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                               Ad adipisci dolores dolorum enim"/>
        </div>
    </div>
</template>

<style scoped>

</style>
