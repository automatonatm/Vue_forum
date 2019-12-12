<template>

</template>

<script>
    import Replies  from  '../components/Replies'
    import SubscribeButton from "../components/SubscribeButton";


    export default {
        name: "Thread",
        props: ['initialRepliesCount', 'dataLocked', 'thread'],
        components : {
            Replies,
            SubscribeButton
        },

        data() {
            return {
                repliesCount: this.initialRepliesCount,
                locked: this.dataLocked
            }
        },

        methods: {
            lock() {


                this.locked =! this.locked

                axios
                    [this.locked ? 'delete' : 'post']
                    ('/lock-thread/'+this.thread.slug+'')
                    /*.then((resp) => {
                    })
                    .catch((error) => {
                        console.log(error)
                    })*/
            }
        }


    }
</script>

<style scoped>

</style>