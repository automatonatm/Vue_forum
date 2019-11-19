<template>

    <div>
        <div class="text-center" v-if="shouldPaginate">
        <ul class="pagination">
            <li class="page-item" @click.prevent="page--"  v-show="prevUrl"><a class="page-link" href="#" rel="prev">&laquo; Previous</a></li>
            <li class="page-item" @click.prevent="page++" v-show="nextUrl"><a class="page-link" rel="next" href="#">Next &raquo;</a></li>
        </ul>
        </div>
    </div>
    
</template>

<script>
    export default {
        name: "paginator",
        props: ['dataSet'],
        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false,

            }
        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page
                this.prevUrl = this.dataSet.prev_page_url
                this.nextUrl = this.dataSet.next_page_url

            },
            page() {
                this.broadcast().updateUrl();
            }

        },
        methods: {
            broadcast() {
                // emit this event to Replies
               return  this.$emit('changed', this.page)

            },
            updateUrl() {
                history.pushState(null, null, '?page='+this.page)
            }

        },
        computed: {
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl
            }
        }
    }
</script>

<style scoped>

</style>