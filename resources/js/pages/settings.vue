<template>
    <div class=" text-right">
        <h2>اعدادت الحساب</h2>

        <div class="alert alert-success" role="alert" v-if="alert">
            {{ alert }}
        </div>

        <div class="alert alert-danger" role="alert" v-if="error">
            {{ error }}
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body  text-right">

                        <div class="form-group">
                            <label for="user-fullname" class="col-form-label">الاسم الكامل:</label>
                            <input type="text"  v-model="user.name"  class="form-control" id="user-fullname">
                        </div>
                        <button class="btn btn-sm btn-primary" @click="ChangeName">تغيير الاسم الكامل</button>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-right">

                        <div class="form-group">
                            <label for="user-password" class="col-form-label">كلمة المرور:</label>
                            <input type="password"  v-model="password" class="form-control" id="user-password">
                        </div>
                        <button class="btn btn-sm btn-primary" @click="ChangePassword">تغير كلمة المرور</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.GetUser();
        },
        data(){
            return {
                user:{},
                password:'',
                alert:null,
                error:null
            }
        },
        methods:{
            async GetUser(){
                try{
                    const response  = await window.axios.get(this.baseurl + '/api/user');
                    this.user = response.data;
                }
                catch (e) {
                    console.log(e.message);
                }
            },
            async ChangePassword(){
                this.alert= null;
                this.error = null;
                try{
                    axios({
                        method: 'post',
                        url: this.baseurl + '/api/changepassword',
                        data: {password:this.password},
                    }).then(response=>{
                        this.alert= response.data.message;
                    }).
                    catch((e)=>{
                        this.error = e.response.data.message;
                    })
                }
                catch (e) {
                    console.log(e.message);
                }
            },
            async ChangeName(){
                this.alert= null;
                this.error = null;
                try{
                    axios({
                        method: 'post',
                        url: this.baseurl + '/api/changename',
                        data: {name:this.user.name},
                    }).then(response=>{
                        this.alert= response.data.message;

                    }).catch((e)=>{
                        this.error = e.message;
                    })
                }
                catch (e) {
                    console.log(e.message);
                }
            }
        }
    }
</script>
