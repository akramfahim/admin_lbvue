<template>
    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card" v-if="$gate.isAdminOrAuthor()">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="addNewModal()">
                        <i class="fas fa-user-plus fa-fw"></i>
                        Add New
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Registered At</th>
                      <th>Updated At</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                      <td>{{ user.id }}</td>
                      <td>{{ user.name | upText }}</td>
                      <td>{{ user.email }}</td>
                      <td><span class="tag tag-success">{{user.type | upText}}</span></td>
                      <td><span class="tag tag-success">{{user.created_at | myDate}}</span></td>
                      <td><span class="tag tag-success">{{user.updated_at | myDate}}</span></td>
                      <td>
                          <a href="#" @click="editModal(user)">
                              <i class="fas fa-edit blue"></i>
                          </a>
                          |
                          <a href="#" @click="deleteUser(user.id)">
                              <i class="fas fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
                <div class="card-footer text-center">
                    <pagination :data="users" @pagination-change-page="getResults" class="m-3"></pagination>
                </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <!-- If User Not admin than show this part -->
        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div>
        

        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editMode" id="exampleModalLongTitle">Update User's Data</h5>
                        <h5 class="modal-title" v-show="!editMode" id="exampleModalLongTitle">Add New</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            
                    <form @submit.prevent="editMode ? updateUser() : createUser() ">

                        <div class="modal-body">
                            
                                <div class="form-group">
                                    <input v-model="form.name" type="text" name="name" placeholder="Name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>

                                <div class="form-group">
                                    <input v-model="form.email" type="email" name="email" placeholder="Email Address"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>

                                <div class="form-group">
                                    <textarea v-model="form.bio" name="bio" placeholder="A Short Description About User (Optional)" 
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }" rows="3"></textarea>
                                    <has-error :form="form" field="bio"></has-error>
                                </div>

                                <div class="form-group">
                                    <select v-model="form.type" name="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                        <option value="">Select User Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">Standard User</option>
                                        <option value="author">Author</option>
                                    </select>
                                    <has-error :form="form" field="type"></has-error>
                                </div>

                            
                                <div class="form-group">
                                    <input v-model="form.password" type="password" name="password"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                    <has-error :form="form" field="password"></has-error>
                                </div>
                            
                                <!-- <button :disabled="form.busy" type="submit" class="btn btn-primary">Log In</button> -->
                            

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" v-show="editMode" class="btn btn-success">Update</button>
                            <button type="submit" v-show="!editMode" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return {
                editMode: false,
                users : {},
                form : new Form({
                    id:'',
                    name:'',
                    email:'',
                    password:'',
                    photo:'',
                    type:'',
                    bio:''
                })
            }
        },
        methods:{
            //Pagination Method
            getResults(page = 1) {
			    axios.get('api/user?page=' + page)
				    .then(response => {
                        this.users = response.data;
                        //return response
				});
		    },
            updateUser(){

                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                .then(() =>{

                    $('#addNew').modal('hide');
                    Swal.fire(
                        'Updated!',
                        'User Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('refreshData');

                })
                .catch(() =>{
                    this.$Progress.fail();
                })

            },

            editModal(user){
                this.editMode  = true;

                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },

            addNewModal(){
                this.editMode = false;

                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        
                        if (result.value) {
                            
                            this.form.delete('api/user/'+id)
                            .then(() => {
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                )
                                Fire.$emit('refreshData');
                            })
                            .catch(() => {
                                Swal(
                                    'Failed',
                                    'User Can not be Deleted',
                                    'warning'
                                )
                            })

                            
                        }

                    })
            },

            loadUsers(){
                if(this.$gate.isAdminOrAuthor()){
                    axios.get('api/user').then( ({ data }) => ( this.users = data ));
                }
                
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then(()=>{
                    
                     //Custom Event for Sending HTTP Request After Creating User
                    Fire.$emit('refreshData');

                    $('#addNew').modal('hide')

                    Toast.fire({
                        icon: 'success',
                        title: 'User Created successfully'
                    })

                    this.$Progress.finish();

                })
                .catch(() =>{

                });
                
               
            }
        },
        created() {

            //For Searching User
            Fire.$on('searching',() => {
                let searchKeyword = this.$parent.search;
                axios.get('api/findData?q='+searchKeyword)
                .then( (data) => {
                    this.users = data.data 
                })
                .catch(() => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Nothing Found'
                    })
                })
            });

            this.loadUsers();
            Fire.$on('refreshData',() => {
                this.loadUsers();
            });
            //Send HTTP Request every 3 seconds
            //setInterval(() => this.loadUsers(), 3000); 

        }
    }
</script>
