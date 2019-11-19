<template>
    <div class="ml-2">


        <div  v-if="canUpdate">
            <form  method="POST"  enctype="multipart/form-data">

                <image-upload  name="avatar" @loaded="onLoad"></image-upload>

                <!--<input type="file" name="avatar" @change="onChange"  accept="image/*">-->

            </form>
        </div>

        <img :src="showAvatar()" :alt="user.name"  height="80" width="80">

    </div>
    
</template>

<script>
    import  imageUpload from  './ImageUpload'
    export default {
        name: "AvatarForm",
        props: ['user', 'avatar_path'],
        components: {
            imageUpload
        },

        data() {
            return {
                avatar: this.user.avatar_path,

                main_path: '/storage/'+this.avatar_path
            }
        },

        computed: {

            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
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

            onLoad(avatar) {
                this.avatar = avatar.src
                this.persist(avatar.file)
            },



            persist(avatar) {
                let form = new FormData();
                form.append('avatar', avatar)
                axios
                    .post('/api/users/'+this.user+'/avatar', form)
                    .then(({data}) => {
                        this.not('Avatar Changed Successfully', 'success')
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },



            showAvatar() {
                if(this.user.avatar_path === null) {
                    return '/storage/avatars/default.png'
                }else {
                    return  '/storage/'+this.user.avatar_path
                }
            }

        }





    }
</script>

<style scoped>

</style>