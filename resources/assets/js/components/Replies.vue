<template>
    <div>
        <div v-for="(reply, index) in items"  :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)" ></reply>

        </div>

        <!--Update coming from Paginate-->
        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <new-reply @created="add" v-if="!  $parent.locked" >

        </new-reply>

        <div v-else>
            <p>This thread has been locked and can no longer receive replies</p>
        </div>

    </div>
</template>

<script>
    import Reply from './Reply'
    import NewReply from "./NewReply";
    import collection from '../mixins/collection'
    export default {
        name: "Replies",
        components: {
            NewReply,
            Reply
        },

        mixins: [collection],
        data() {
            return {
                items: [],
                dataSet: false,
            }
        },
        created() {
            this.fetch()
        },
        methods: {
            not(msg, type) {
                new Noty({
                    type: '' + type + '',
                    text: '' + msg + '',
                    timeout: '4000',
                    progressBar: true,
                    closeWith: ['click'],
                    killer: true,
                    animation: {
                        open: 'animated bounceInRight', // Animate.css class names
                        close: 'animated bounceOutRight' // Animate.css class names
                    }
                }).show();

            },
            remove(index) {
                this.items.splice(index, 1)
                this.$emit('removed')
                this.not('Reply Deleted', 'info')
            },

            add(item) {
                this.items.push(item)

                //fire event in in Reply
                this.$emit('add')
            },
            url(page) {
                // if page was not given
                if(! page) {
                    let query =  location.search.match(/page=(\d+)/)
                    page = query ? query[1] : 1
                }
                return `${location.pathname}/replies?page=${page}`;
            },


            fetch(page) {
                axios
                    .get(this.url(page))
                    .then(this.refresh)
            },

            refresh({data}) {
                this.dataSet = data
                this.items  = data.data
                window.scroll(0, 0)

            }







        }

    }
</script>

<style scoped>

</style>