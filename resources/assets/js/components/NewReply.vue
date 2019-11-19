<template>



    <div>
        <div v-if="signedIn">
            <div class="col-md-12 card-body">

                <div class="form-group">
                <textarea
                        v-model="body"
                        rows="5" id="body"
                        placeholder="Have something to say?"
                        required
                        class="form-control">
                </textarea>
                </div>

                <button @click="addReply" class="btn btn-primary">Post</button>
            </div>
        </div>
        <p v-else class="text-center mt-2 font-weight-bold">Please <a href="/login">Sign In</a> in to participate in this discussion </p>

    </div>



  <!--
   -->

</template>

<script>

    import  'at.js'
    import  'jquery.caret'
    export default {
        name: "NewReply",

        data(){
            return {
                body: '',
                endpoint: location.pathname+'/replies'
            }
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

            addReply() {
                axios
                    .post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = ''

                        this.not('Reply Posted.', 'success')

                        //fire an event in Replies
                        this.$emit('created', data)
                    })
                    .catch((error) => {
                        if(error.response.status === 403) {
                            this.not('You are posting to frequently', 'error')
                        }else {
                            this.not(''+error.response.data.errors.body+'', 'error')
                        }
                    })
            }
        },

        mounted() {

               $(document).ready(function () {

                   $('#body').atwho({
                       at: '@',
                       delay: 750,
                       callbacks: {
                           remoteFilter: function(query, callback) {

                                 $.getJSON('/api/users', {name: query}, function (usernames) {
                                     callback(usernames)
                                 });

                           }
                       }

                   });
               });

        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
        }
    }
</script>

<style scoped>

</style>