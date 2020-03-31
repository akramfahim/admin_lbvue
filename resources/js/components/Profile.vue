<style>
    .widget-user-header{
        background-position: center center;
        background-size: cover;
        height: 300px !important;
    }
    .widget-user-image{
        left: 48% !important;
        top: 120px !important;
        margin-left: -45px !important;
    }
    .widget-user-image img{
        height: 150px !important;
        width: 150px !important;
    }
    .card-footer{
        padding-top: 0px !important;
    }
</style>
<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
               

                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background: url('./img/user-cover.jpg')">
                        <h3 class="widget-user-username text-right">Elizabeth Pierce</h3>
                        <h5 class="widget-user-desc text-right">Web Designer</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                            <h5 class="description-header">3,200</h5>
                            <span class="description-text">SALES</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                            <h5 class="description-header">13,000</h5>
                            <span class="description-text">FOLLOWERS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                            <h5 class="description-header">35</h5>
                            <span class="description-text">PRODUCTS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>



                <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              
              <div class="card-body">
                
                <div class="tab-content">
                    <div class="tab-pane" id="activity">
                    

                        <!-- Post -->
                        <div class="post clearfix">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="img/user.png" alt="User Image">
                                <span class="username">
                                <a href="#">Sarah Ross</a>
                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                </span>
                                <span class="description">Sent you a message - 3 days ago</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Lorem ipsum represents a long-held tradition for designers,
                                typographers and the like. Some people hate it and argue for
                                its demise, but others ignore the hate as they create awesome
                                tools to help create filler text for everyone from bacon lovers
                                to Charlie Sheen fans.
                            </p>

                            <form class="form-horizontal">
                                <div class="input-group input-group-sm mb-0">
                                <input class="form-control form-control-sm" placeholder="Response">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-danger">Send</button>
                                </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.post -->

                    
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane active" id="settings">
                        
                        <form>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" v-model="form.name" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error> 
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" v-model="form.email" id="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error> 
                            </div>
                            <div class="form-group">
                                <label for="experience">Describe Experiences</label>
                                <textarea v-model="form.bio" id="experience" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="photo">Example file input</label>
                                <input type="file" @change="updatePhoto" class="form-control-file" id="photo">
                            </div>
                            <div class="form-group">
                                <label for="passport">Passport (Leave Empty If Not Changing)</label>
                                <input type="password" v-model="form.password" id="passport" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error> 
                            </div>

                            <button type="submit" @click.prevent="updateInfo" class="btn btn-success">Update</button>
                        </form>

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>


                

            </div>
        </div>
    </div>
</template>

<script>
    //import Form from 'vForm'
    export default {
        
        data(){
            return {
                //currentPhoto:'',
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
            getProfilePhoto(){
                //  let profilePhoto = (this.form.photo.match(/\//) ? this.currentPhoto : this.form.photo);
                // return "img/profile/"+profilePhoto;

                let prefix = (this.form.photo.match(/\//) ? '' : '/img/profile/');
                return prefix + this.form.photo;
            },
            updateInfo(){

                this.$Progress.start();

                this.form.put('api/profile')
                .then(()=>{
                    Swal.fire(
                        'Updated!',
                        'User Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterUpdate');
                })
                .catch(()=>{
                    this.$Progress.fail();
                    toast({
                      type: 'error',
                      title: 'Something went wrong!'
                      })
                });
            },
            updatePhoto(e){
                let file = e.target.files[0];
                //console.log(file);
                // console.log(file['size'] );

                //Check whether the file size is less than 2MB
                if(file['size'] < 2097152){
                    let reader = new FileReader();
                    reader.onloadend = (file) => {
                        //console.log('RESULT', reader.result)
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire(
                        'Oops!',
                        'File Should Not Be Larger than 2mb.',
                        'error'
                    )
                }
            }
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.$Progress.start();
            axios.get('api/profile')
            .then( ({ data }) => {
                this.$Progress.finish();
                //this.currentPhoto = data.photo;
                this.form.fill(data) 
            })
            .catch(() => {
                this.$Progress.fail();
            });
        }

        // created() {
        //   this.$Progress.start()
        //     axios.get('api/profile')
        //     .then(({ data }) => {
        //       this.currentPhoto = "img/profile/" + data.photo;
        //       Fire.$on('AfterUpdate', () => {
        //         axios.get('api/profile')
        //         .then((data) => {
        //           let photo = data.photo
        //           this.currentPhoto = "img/profile/" + photo;
        //         })
        //       })
        //       this.form.reset();
        //       this.form.fill(data)
        //       this.$Progress.finish();
        //     })
        //     .catch(() => {
        //       this.$Progress.fail();
        //     })
        // }
    }
</script>
