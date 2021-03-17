<template>
<div class="d-flex flex-column justify-content-center align-items-center h-100 mt-5">
    <div class="w-50">
            <div class="card">
                <div class="card-header bg-warning">
                    Login
                </div>
                <div  class="card-body">
                    <div v-if="error != ''" class="alert alert-danger">
                        <span>{{error}}</span>
                    </div>
                    <form @submit.prevent="loginUser">
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" v-model="email" name="email" placeholder="Enter email"  class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="Email">Password</label>
                            <input type="password" v-model="password" name="password" placeholder="*****"  class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-warning float-right">Login</button>

                    </form>
                </div>
                </div>
        </div>
</div>
        
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                password: '',
                error: ''
            }
        },
        mounted() {},
        methods: {
            loginUser() {
                axios.post('/api/login', {email: this.email, password: this.password}).then((res) => {
                    if(res.data.success) {
                        alert(res.data.message)
                    this.$router.go({name: 'Dashboard', params: {name: 'Ojo'}})
                    } else {
                        this.setError(res.data.message)
                    }
                    
                }).catch((err) => {
                    this.setError(err.response.data.message)
                })
            },
            setError(message) {
                this.error = message
                setInterval(() => {
                    this.error = ''
                }, 3500)
            }
        }
    }
</script>
