<template>

   <!-- <reply :attributes="{{$reply}}" inline-template route="{{route('reply.patch', $reply->id)}}" v-cloak>
-->
        <div class="card mt-2 mb-2" :id="'reply-'+id">
            <div class="card-header">
                <div class="">

                    <a :href="'/profiles/'+data.user.name">{{data.user.name}}</a>

                    at {{ago }}...
                    <!--{{$reply->created_at->diffForHumans()}}-->


                </div>

            </div>

            <div class="card-body">

                <div data-delay="5" v-if="editing">
                    <form @submit.prevent="update">

                        <div class="form-group animated fadeIn" data-delay="5">
                            <textarea class="form-control" v-model="data.body" required></textarea>
                        </div>
                        <button  class="btn btn-sm btn-success">Update</button>
                        <button @click="editing = false" type="button" class="btn btn-sm btn-primary">Cancel</button>

                    </form>

                </div>

                <div v-else>
                    <div class="" v-html="data.body"></div>
                </div>

            </div>

            <div class="card-footer" >


                <div v-if="canUpdate">
                <button @click="editing = true" class="btn btn-sm btn-primary ml-3">Edit</button>
                <button @click="destroy" class="btn btn-sm btn-danger">X</button>
               </div>

                <favorite v-if="signedIn" :reply="data"></favorite>
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

        props: ['data', 'route'],
        data() {
            return {
                editing: false,
                reply: this.data,
                id: this.data.id
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            canUpdate() {
              return this.authorize(user => this.data.user.id === user.id)

            },
            ago() {
                return moment(this.data.created_at).fromNow()
            }
        },
        methods: {
            update() {
                axios
                    .patch('/replies/'+this.reply.id+'', {
                        body: this.reply.body
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

            }
        }
    }
</script>

<style scoped>

</style>