<template>

</template>

<script>
    export default {
        name: "Alert",
        props: ['message'],
        data() {
            return {
                msg: this.message,
                show: false
            }
        },
        mounted() {
            if(this.show) {
                this.not(this.msg)
            }

        },
        methods: {
            not(msg) {
                new Noty({
                    type: 'info',
                    text: ''+msg+'',
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

        },
        created() {
            if (this.message)  {
                this.msg = this.message
                this.show = true
            }
        }

    }
</script>

<style scoped>

</style>