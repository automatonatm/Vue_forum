<template>

    <div>
        <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown" v-if="notifications.length">

            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
               <span class="badge-success" v-text=""><i class="fa-1x fa fa-bell"></i></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"  >
                <a class="dropdown-item" :href="notification.data.link" v-for="notification in notifications" @click="markAsRead(notification)">
                    {{ notification.data.message }}
                </a>

            </div>
        </li>
        </ul>

    </div>
    
</template>

<script>
    export default {
        name: "UserNotifications",
        data() {
            return {
                notifications: false,
                count: '',
            }
        },

        created() {
            axios
                .get('/profiles/'+window.App.user.name+'/Notifications')
                .then(({data}) => {
                    this.notifications = data;
                    this.count = this.notifications.length

                })

                .catch((error) => {
                    console.log(error)
                })
        },

        methods: {
            markAsRead(notification) {
                axios
                    .delete('/profiles/'+window.App.user.name+'/Notifications/'+notification.id+'')
            },

        }
    }
</script>

<style scoped>

</style>