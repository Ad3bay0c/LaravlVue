<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        Ths is the dashboard
                        {{ user.email}}
                        <button @click="logout">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
           this.getUser();
        },
        data() {
            return {
                user: {},
                nanme: ''
            }
        },
        methods: {
            getUser() {
                axios.get('/api/user').then((res) => {
                    this.user = res.data
                }).catch((err) => {
                    console.log(err.response)
                })
            },
            logout(){
                axios.post('/api/logout').then(() => {
                    this.$router.go('/');
                })
            }
        }
    }
</script>
