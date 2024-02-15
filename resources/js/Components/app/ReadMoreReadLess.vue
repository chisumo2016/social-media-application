<script setup>

import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";

defineProps({
    content:String,
    contentClass:String
})
</script>

<template>
    <Disclosure v-slot="{ open }">

        <div
            v-if="!open"
             v-html="content.substring(0,200)"
             class="ck-content-output" :class="contentClass"/>

        <template v-if="content && content.length > 200">
            <DisclosurePanel>
                <div v-html="content" class="ck-content-output"/>
            </DisclosurePanel>
            <div class="flex justify-end">
                <DisclosureButton  class="hover:underline ">
                    {{ open ? 'Read Less' : 'Read More'}}
                </DisclosureButton>
            </div>
        </template>
    </Disclosure>
</template>

<style scoped>

</style>
