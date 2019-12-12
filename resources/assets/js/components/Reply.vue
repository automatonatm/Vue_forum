<template>

   <!-- <reply :attributes="{{$reply}}" inline-template route="{{route('reply.patch', $reply->id)}}" v-cloak>
-->
        <div class="card mt-2 mb-2  " :id="'reply-'+id">
            <div :class=" isBest ? 'card-header bg-success': 'card-header' ">
                <div class="">
                    <a :href="'/profiles/'+reply.user.name">{{reply.user.name}}</a>
                    at {{ago }}...
                    <!--{{$reply->created_at->diffForHumans()}}-->
                    <div class="float-right" v-if="authorize('owns', reply.thread)">
                        <button  v-show="! isBest"  class="btn btn-sm btn-primary" @click="markBestReply">BEST REPLY?</button>
                    </div>

                    <span class="text-primary text-bold float-right" v-show="isBest"><i class="fa fa-happy"></i>BEST REPLY</span>

                </div>

            </div>

            <div class="card-body">

                <div data-delay="5" v-if="editing">
                    <form @submit.prevent="update">

                        <div class="form-group animated fadeIn" data-delay="5">
                            <textarea class="form-control" v-model="body" required></textarea>
                        </div>

                        <button  class="btn btn-sm btn-success">Update</button>
                        <button @click="editing = false" type="button" class="btn btn-sm btn-primary">Cancel</button>

                    </form>

                </div>

                <div v-else>
                    <div class="" v-html="body"></div>
                </div>

            </div>

            <div class="card-footer" >

                <div v-if="authorize('owns', reply) ">
                <button @click="editing = true" class="btn btn-sm btn-primary ml-3">Edit</button>
                <button @click="destroy" class="btn btn-sm btn-danger">X</button>

               </div>

                <favorite v-if="signedIn" :reply="reply"></favorite>
            </div>
        </div>

   <!-- </reply>-->
</template>

<script>
    import favorite from './Favorite'
    import  moment from 'moment'

    export default {
        name: "Reply",
        components: {
            favorite
        },

        props: ['reply', 'route'],
        data() {
            return {
                editing: false,
                id: this.reply.id,
                thread: window.thread,
                body: this.reply.body,
                //isBest: this.data.isBest
            }
        },



        computed: {
            /*
            signedIn() {
                return window.App.signedIn;
            },*/
           /* canUpdate() {
              return this.authorize(user => this.data.user.id === user.id)

            },*/
            ago() {
                return moment(this.reply.created_at).fromNow()
            },

            isBest(){
               return  this.thread.best_reply_id === this.id;
            }
        },
        methods: {
            update() {
                axios
                    .patch('/replies/'+this.reply.id+'', {
                        body: this.body
                    })
                    .then((resp) => {
                        this.editing = false
                        this.not('Updated', 'info')
                    })
                    .catch((error) => {
                       // console.log(error.response.data.errors.body);
                        this.not(''+error.response.data.errors.body+'', 'error')
                    })
            },

            destroy() {
                axios
                    .delete('/replies/'+this.reply.id+'')
                    .then((resp) => {
                        this.editing = false
                        this.$emit('deleted', this.data.id)

                       /* $(this.$el).fadeOut(300, () => {
                            this.not('Reply Deleted', 'info')
                        })*/


                    })
                    .catch((error) => {
                        console.log(error);
                        this.not('An error has occurred, Try again later', 'error')

                    })
            },
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

            markBestReply() {
                axios
                    .post('/replies/'+this.data.id+'/best', {})
                    .then((resp) => {
                      //  this.$emit('best-reply-selected', this.data.id);
                        this.not('Marked as best reply', 'success')
                        this.thread.best_reply_id = this.id
                    })
                    .catch((error) => {
                        if(error.response.status === 500) {
                            this.not('An error has occurred, Try again later', 'error')
                        }
                        console.log(error);


                    })
            }
        }
    }
</script>

<style scoped>

</style>