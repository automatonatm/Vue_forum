<template>

    <div>

            <button  :class="classes" @click="subscribe">{{active ? 'UnFollow' : 'Follow'}}</button>

    </div>


    
</template>

<script>
    export default {
        name: "SubscribeButton",
        props: ['active'],
        data() {
            return {

            }
        },
        computed: {
            classes() {
                return ['btn', this.active ? 'btn-success' : 'btn-primary']
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


            subscribe() {

                axios
                    [(this.active ? 'delete' : 'post')]
                (location.pathname+'/subscribe')
                    .then(({data}) => {
                        this.active =!  this.active

                       // this.active ?  this.not('You are now following this thread', 'success') : this.not('You have stopped following this thread', 'info')

                    })
                    .catch((error) => {
                        console.log(error)
                    })

            }
        }

    }
</script>

<style scoped>

</style>