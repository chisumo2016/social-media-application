<script setup>
import PostItem from "@/Components/app/PostItem.vue";

defineProps({
    posts: Array
})
const  post1 = {
    user: {
        id:1,
        avatar:'https://randomuser.me/api/portraits/women/65.jpg',
        name: 'James Way'
    },
    group: {
        id: 1,
        name:'Laravel Developer'
    },
    attachments:[
        {
            id: 1,
            name: 'test.png',
            mime:'image/png',
            url:'https://picsum.photos/1000'
        },

        {
            id: 2,
            name: 'test2.png',
            mime:'image/png',
            url:'https://picsum.photos/1000'
        },

        {
            id: 3,
            name: 'MyDocument.docx',
            mime:'#',
            url:'application/msword'
        },
    ],

    body: `
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium blanditiis commodi dolore eius impedit ipsam labore laborum mollitia necessitatibus, nobis odio pariatur quam quasi quis quos

            sit ullam ut veniam.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium blanditiis commodi dolore eius impedit ipsam labore laborum mollitia necessitatibus, nobis odio pariatur quam quasi quis quos

            sit ullam ut veniam.</p>

`,
    created_at: '2024-01-24 15:48'
}

const  post2 = {
    user: {
        id:1,
        avatar:'https://randomuser.me/api/portraits/women/63.jpg',
        name: 'John Smith'
    },
    group:null,

    body: `
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium blanditiis commodi dolore eius impedit ipsam labore laborum mollitia necessitatibus, nobis odio pariatur quam quasi quis quos

            sit ullam ut veniam.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium blanditiis commodi dolore eius impedit ipsam labore laborum mollitia necessitatibus, nobis odio pariatur quam quasi quis quos

            sit ullam ut veniam.</p>

`,
    created_at: '2024-01-24 15:48'
}
</script>

<template>
<div>
    <div class="overflow-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post"/>
    </div>
</div>
</template>

<style scoped>

</style>
