<template>
    <div>
        <h2>قائمة المستخدمين</h2>

        <div class="alert alert-success" role="alert" v-if="alert">
            {{ alert }}
        </div>

        <div class="alert alert-danger" role="alert" v-if="error">
            {{ error }}
        </div>

        <div class="form-group mt-4">
            <button class="btn btn-primary"  data-toggle="modal" data-target="#ModelAddProduct">انشاء حساب</button>
        </div>
        <div class="form-group mt-4 table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الاسم الكامل</th>
                    <th scope="col">البريد الالكتروني</th>
                    <th scope="col">الاحداث</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="row in users">
                    <th scope="row">{{ row.id }}</th>
                    <td>{{ row.name }}</td>
                    <td>{{ row.email }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm dropdown-toggle"
                                type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            الاحداث
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item" @click="EditUser(row)" data-toggle="modal" data-target="#ModelAddProduct">تعديل</button>
                            <button class="dropdown-item" @click="DeleteUser(row)">حدف</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="ModelAddProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">انشاء مستخدم</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">


                                <div class="form-group">
                                    <label for="user-name" class="col-form-label">الاسم الكامل</label>
                                    <input type="text"  v-model="user.name" class="form-control" id="user-name">
                                </div>

                                <div class="form-group">
                                    <label for="user-email" class="col-form-label">البريد الالكتروني</label>
                                    <input type="text"  v-model="user.email" class="form-control" id="user-email">
                                </div>

                                <div class="form-group">
                                    <label for="user-password" class="col-form-label">كلمة المرور</label>
                                    <input type="text"  v-model="user.password" class="form-control" id="user-password">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="button" class="btn btn-primary"  data-dismiss="modal" @click="AddUser">حفظ التغيير</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
    export default {
        mounted() {
            this.fetch_users();
        },
        data() {
             return {
                 users:[],
                 user:{
                     name:'',
                     email:'',
                     password:''
                 },
                 olduser:{},
                 edit:false,
                 error:null,
                 alert:null,
                    }
        },
        methods:{
            async fetch_users(){
                try{
                    const response  = await window.axios.get(this.baseurl + '/api/users');
                    this.users = response.data;
                }
                catch (e) {
                    console.log(e.message);
                }
            },
            async AddUser(){
                if(!this.edit){
                    const data = new FormData();
                    data.append("file", this.file);
                    for ( var key in  this.user ) {
                        data.append(key, this.user[key]);
                    }

                    try{
                        var response = await axios({
                            method: 'post',
                            url: this.baseurl + '/api/users',
                            data: data,
                        })

                        this.users.unshift(response.data.data);

                        this.ResetUser()

                        this.alert = response.data.message;
                    }
                    catch (e) {
                        this.error = e.response.data.message;
                    }
                }
                else
                {
                    const data = new FormData();
                    data.append("_method", 'PATCH');
                    for (var key in  this.user) {
                        data.append(key, this.user[key]);
                    }

                    try {
                        var response = await axios({
                            method: 'post',
                            url: this.baseurl + '/api/users/' + this.user.id,
                            data: data,
                        })

                        this.olduser = (response.data.data);
                        this.ResetUser()
                        this.alert = response.data.message;

                    } catch (e) {
                        this.ResetUser()
                        this.error = e.response.data.message;
                    }

                }


            },
            ResetUser(){
                this.Reload(this.user,this.olduser)

                this.user ={
                    name:'',
                    email:'',
                    password:''
                }

                this.edit=false;

                this.error = null;
                this.alert = null;
            },
            EditUser(item){
                this.olduser = this.Clone(item);
                this.user = item;
                this.edit = true;
            },
            DeleteUser(item){

                window.axios.delete(this.baseurl + "/api/users/" + item.id).then(( reponse ) => {
                    this.users.splice(this.users.indexOf(item),1);
                    this.alert = reponse.data.message;
                }).
                catch((error)=>{
                    this.error = error.response.data.message;
                });
            }

            ,
            Clone(item){

                return {
                    name:item.name,
                    email:item.email,
                    password:''
                }

            },
            Reload(olditem,newitem){
                olditem.name = newitem.name;
                olditem.email = newitem.email;
                olditem.password = '';


            },

        }
    }
</script>
