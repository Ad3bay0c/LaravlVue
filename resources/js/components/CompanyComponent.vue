<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" v-if="success">{{success}}</div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <strong class="flex">List of Comapnies</strong>
                    
                        <button type="button" class=" flex btn btn-warning btn-sm" @click="addButton">Add</button>
                     </div>

                    <div class="card-body">
                       <ul  class="list-group">
                            <li v-for="company in companies"  v-bind:key="company.id" class="list-group-item">
                                <div class="d-flex justify-content-between">
                                <td class="flex">
                                    <a @click.prevent="viewCompany(company.id, company.name)">{{company.name }}</a>
                                    
                                    </td>
                                <td class="flex">{{ company.email }}</td>
                                <!-- <td class="flex">{{ company.created_at }}</td> -->
                                <td class="flex">
                                    <button class="btn btn-primary btn-sm" @click="viewCompany(company.id, company.name)">View</button>

                                    <button class="btn btn-success btn-sm" @click="editButton(company.id)">Edit</button>

                                    <button @click="deleteCompany(company.id)" class=" btn btn-danger btn-sm">Delete</button>
                                </td>
                                </div>
                                
                                <!-- {{company.name}} -->
                                </li>
                    
                        </ul>
                       <pagination :data="laravelData" @pagination-change-page="getCompanies"></pagination>
                    </div>
                </div>
            </div>
        
        </div>

        <!-- Button trigger modal -->


<!-- Modal -->
         <div class="modal fade" id="addCompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ edit ? 'Edit Company ' : ' Add New Company'}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form @submit.prevent="edit ? editCompany() : addCompany()" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="container-fluid w-100%">
                    <div class="alert alert-danger" v-if="errors">{{errors}}</div>
                    
                </div>
                    
                    <div class="form-group">
                            <label for="Email">Name </label>
                            <input v-bind:class="error ? 'is-invalid' : ''" @keydown="error = ''" v-model="company.name" type="text" name="name" placeholder="Enter Company Name"  class="form-control" required/>
                            <span class="text-danger" v-if="!!error">{{error}}</span>
                        </div>
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" v-model="company.email" name="email" placeholder="Enter Company Email"  class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="Email">Website</label>
                            <input type="text" v-model="company.website" name="website" placeholder="Enter Company Official Website"  class="form-control"/>
                        </div>
                        
                        <div v-if="!edit" class="form-group">
                            <label for="Email">Logo</label>
                            <input type="file" name="logo" v-on:change="uploadImage" accept="image/*" class="form-control"/> <span>100 x 100 minimum</span>
                        </div>

                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-warning">{{ edit ? 'Edit Company ' : ' Add Company'}}
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
           this.getUser();
           this.getCompanies();
        },
        data() {
            return {
                companies: [],
                laravelData: {},
                company: {},
                error: '',
                errors: '',
                success: '',
                logo: '',
                edit: ''
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
            getCompanies(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
                axios.get('/api/company?page='+page).then((res) => {
                    // console.log(res.data)
                    this.companies = res.data.data
                    this.laravelData = res.data
                }).catch((err) => {
                    console.log(err.response.data.message)
                })
            },
            addButton() {
                this.company = {};
                this.edit = "";
                $('#addCompany').modal('show');
            },
            addCompany(){
                if(this.company.name == '' || this.company.name.trim() == '' || this.company.name.length < 1) {
                    this.error = 'Name Field Required'
                } else {
                    
                    let data = new FormData();
                    data.append('name', this.company.name);
                    data.append('email', this.company.email);
                    data.append('website', this.company.website);
                    data.append('file', this.logo);
                    const config = {
                        headers: { 'content-type': 'multipart/form-data' }
                    };
                    // console.log(this.logo)
                    axios.post('/api/company', data, config).then((res) => {
                        this.setSuccess(res.data.message);
                        // console.log(res.data)
                        $('#addCompany').modal('hide');
                        this.getCompanies();
                    }).catch((err) => {
                        this.errors = err.response.data.message
                        console.log(err.response)
                    })
                   
                }
            },
            uploadImage(e) {
                this.logo = e.target.files[0];
            },
            setSuccess(message) {
                this.success = message;
                setInterval(() => {
                    this.success = ''
                }, 4500)
            },
            editButton(id) {
                
                this.edit = 'show';
                axios.get('/api/company/'+id).then((res) => {
                    // console.log(res.data)
                    this.company = res.data.data;
                    $('#addCompany').modal('show');
                })
            }, 
            deleteCompany(id) {
                if(confirm("Are you sure ?")){
                   axios.delete('/api/company/'+id).then((res) => {
                       this.setSuccess(res.data.message)
                       this.getCompanies();
                   }).catch((err) => {
                       this.setSuccess(err.response.data.message)
                   });
                }
            },
            editCompany() {
                if(this.company.name == '' || this.company.name.trim() == '' || this.company.name.length < 1) {
                    this.error = 'Name Field Required'
                } else {
                    const data = {
                        name: this.company.name,
                        email: this.company.email,
                        website: this.company.website,
                    };
                    // console.log(this.logo)
                    axios.put('/api/company/'+this.company.id, data).then((res) => {
                        this.setSuccess(res.data.message);
                        // console.log(res.data)
                        $('#addCompany').modal('hide');
                        this.company = {};
                        this.edit = "";
                        this.getCompanies();
                    }).catch((err) => {
                        this.errors = err.response.data.message
                    })
            }
        }, 
        viewCompany(id, name) {
            this.$router.push({name: "companyDetails", params: {
                id: id, name: name
            }})
        }
        }
    }
</script>
