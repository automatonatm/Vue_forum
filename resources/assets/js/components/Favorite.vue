<template>
    <div>

        <button :class="classes" @click="toggle">
           {{favouriteCount}}
            <i class="fa fa-heart"></i>
        </button>

    </div>
</template>

<script>
    export default {
        name: "Favorite",
        props: ['link', 'reply'],
        data() {
            return {
                favouriteCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },
        mounted() {
            //console.log(this.link)
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
            toggle() {
                return !this.isFavorited ? this.destroy() : this.create()
            },

            create() {
                axios
                    .delete(this.endpoint)
                    .then((resp) => {
                        this.isFavorited = false
                        this.favouriteCount--

                    })

                    .catch((error) => {

                    })
            },
            destroy() {
                axios
                    .post(this.endpoint)
                    .then( (resp) => {
                        this.isFavorited = true
                        this.favouriteCount++

                    })
            }
        },
        computed: {
            classes() {
                return ['btn btn-sm float-right', this.isFavorited ? 'btn-success': 'btn-primary']
            },
            endpoint() {
                return '/replies/'+this.reply.id+'/favorites'
            }

        }
    }
</script>

<style scoped>

</style>