<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" v-if="success">{{success}}</div>
                <div>
                    <h4> {{name}}</h4>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <strong class="flex">List of Employees </strong>
                    
                        <button type="button" class=" flex btn btn-warning btn-sm" @click="clickModal">Add</button>
                     </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="employe in employees" v-bind:key="employe.id">
                                        <td>{{ employe.firstname}}</td>
                                        <td>{{ employe.lastname}}</td>
                                        <td>{{ employe.email}}</td>
                                        <td>{{ employe.phone}}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" @click="editEmployee(employe.id)">Edit</button>
                                            <button class="btn btn-danger btn-sm" @click="deleteEmployee(employe.id)">Delete</button>
                                        </td>
                                    </tr>
                                    <pagination :data="laravelData" @pagination-change-page="getEmployees"></pagination>
                                </tbody>
                            </table> 
                        </div>
                                             
                    </div>
                </div>
            </div>
        
        </div>

        <!-- Button trigger modal -->


<!-- Modal -->
         
         <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ edit ? 'Edit Employee' : 'Add New Employee'}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form @submit.prevent="edit ? updateEmployee() : addEmployee()">
            <div class="modal-body">
                <div class="container-fluid w-100%">
                    <div class="alert alert-danger" v-if="errors">{{errors}}</div>
                    
                </div>
                    
                    <div class="form-group">
                        <label for="firstname">First Name </label>
                        <input v-bind:class="checkError ? 'is-invalid' : ''" @keydown="checkError = ''; error.firstname=''" v-model="employee.firstname" type="text" name="firstname" placeholder="Enter Employee First Name"  class="form-control" required/>
                        <span class="text-danger" v-if="error.firstname">{{error.firstname}}</span>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name </label>
                        <input v-bind:class="checkLast || error.lastname ? 'is-invalid' : ''" @keydown="checkLast = ''; error.lastname=''" v-model="employee.lastname" type="text" name="lastname" placeholder="Enter Employee Last Name"  class="form-control" required/>
                        <span class="text-danger" v-if="error.lastname">{{error.lastname}}</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" v-model="employee.email" name="email" placeholder="Enter Employee Email"  class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="number" v-model="employee.phone" name="phone" placeholder="Enter Employee Phone"  class="form-control"/>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">{{ edit ? 'Edit Employee ' : ' Add Employee'}}
                    </button>
            </div>
             </form>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.id = this.$route.params.id;
            this.name = this.$route.params.name;
           this.getEmployees();
           
        },
        data() {
            return {
                employees: [],
                employee: {},
                error: {},
                errors: '',
                success: '',
                edit: '',
                id: "",
                name: "",
                checkError: '',
                checkLast: '',
                laravelData: {}
            }
        },
        methods: {
            getEmployees(page){
                if(typeof page === 'undefined'){
                    page = 1;
                }
                axios.get('/api/getEmployee/'+this.id+'?page='+page).then((res) => {
                    // console.log(res.data.data)
                    // this.name = res.data.data.name;
                    this.employees = res.data.data;
                    this.laravelData = res.data;
                }).catch((err) => {
                    console.log(err.response.data)
                })
            },
            getName() {
                
            },
            setSuccess(message) {
                this.success = message;
                setInterval(() => {
                    this.success = ''
                }, 4500)
            },
            validate(){
                if(this.employee.firstname == '' || this.employee.firstname.trim() == '' || this.employee.firstname.length < 1 ) {
                     this.checkError = 1;
                    this.error.firstname = 'First Name Field Required'
                } 
                if(this.employee.lastname == '' || this.employee.lastname.trim() == '' || this.employee.lastname.length < 1 ) {
                     this.checkLast = 1;
                    this.error.lastname = 'Last Name Field Required'
                }
            },
            addEmployee() {
                 this.validate();
                if(!this.checkError && !this.checkLast){
                    const data = {
                        firstname: this.employee.firstname,
                        lastname: this.employee.lastname,
                        email: this.employee.email,
                        phone: this.employee.phone,
                        company: this.id
                    }

                    axios.post('/api/employee', data).then((res) => {
                        this.setSuccess(res.data.message);
                        this.error = {};
                        this.closeModal();
                        this.getEmployees();
                    }).catch((err) => {
                        this.errors = err.response.data.message
                    })
                }
                
            },
            editEmployee(id){
                this.edit = 'show';
                axios.get('/api/employee/'+id).then((res) => {
                    this.employee = res.data.employee;
                    $('#employeeModal').modal('show');
                }).catch((err) => {
                    console.log(err.response.data.message)
                })
            },
            updateEmployee() {
                this.validate();
                if(!this.checkError && !this.checkLast){
                    const data = {
                            firstname: this.employee.firstname,
                            lastname: this.employee.lastname,
                            email: this.employee.email,
                            phone: this.employee.phone,
                            company: this.id
                        }

                    axios.put('/api/employee/'+this.employee.id, data).then((res) => {
                        this.getEmployees();
                        this.setSuccess(res.data.message);
                        this.closeModal();
                    }).catch((err) => {
                        this.errors = err.response.data.message
                    })
                }
            },
            deleteEmployee(id) {
                if(confirm('Are you sure you want to delete?')){
                    axios.delete('/api/employee/'+id).then((res) => {
                        this.setSuccess(res.data.message);
                        this.getEmployees();
                    }).catch((err) => {
                        console.log(err.response.data.message);
                    })
                }
            },
            clickModal() {
                this.employee = {};
                this.edit = "";
                this.checkError = '',
                this.error = {},
                $('#employeeModal').modal('show')
            }, 
            closeModal() {
                this.employee = {};
                this.edit = "";
                $('#employeeModal').modal('hide')
            }
        }
    }
</script>
