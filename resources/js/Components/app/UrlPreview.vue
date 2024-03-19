<!--CHILD COMPONENT-->

<script setup>
defineProps({
    preview: Object,
    url:String
})
</script>

<template>
    <a
        v-if="preview && preview.title"
        :href="url"
        target="_blank"

        class="block mt-4 border border-indigo-200 bg-indigo-50">
        <!-- <pre>{{urlPreview}}  form.preview. </pre> -->
        <img :src="preview.image"
             :alt="preview.title"
             class="max-w-full">
        <div class="p-2">
            <h3 class="font-semibold">{{ preview.title}}</h3>
            <p class="text-sm m-none">{{ preview.description}}</p>
        </div>
    </a>

    <a
        v-else-if="url"
        :href="url"
        target="_blank"

        class="block mt-4 border border-indigo-200 bg-indigo-50">
        <div class="p-2">
            {{ url}}
        </div>
    </a>
</template>

<style scoped>

</style>
