<template>
    <div>
        <h2>قائمة الاقسام</h2>

        <select  class="form-control float-right text-right  d-inline-block col-md-4 m-2" @change="fetch_categories" v-model="user_id" v-if="isadmin">
            <option  value="">اختر المستخدم</option>
            <option   v-for="user in users" :value="user.id">{{ user.name }}</option>
        </select>
        <div class="clearfix"></div>

        <div class="alert alert-success" role="alert" v-if="alert">
            {{ alert }}
        </div>

        <div class="alert alert-danger" role="alert" v-if="error">
            {{ error }}
        </div>

        <div class="form-group mt-4">
            <button class="btn btn-primary"  data-toggle="modal" data-target="#ModelAddProduct">انشاء قسم</button>
        </div>
        <div class="form-group mt-4 table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">اسم القسم</th>
                    <th scope="col">الوصف</th>
                    <th scope="col">الاحداث</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="row in categories">
                    <th scope="row">{{ row.id }}</th>
                    <td>{{ row.name }}</td>
                    <td>{{ row.description }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm dropdown-toggle"
                                type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            الاحداث
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item" @click="EditCategory(row)" data-toggle="modal" data-target="#ModelAddProduct">تعديل</button>
                            <button class="dropdown-item" @click="DeleteCategory(row)">حدف</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">انشاء قسم</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="product-name" class="col-form-label">الاسم:</label>
                                    <input type="text"  v-model="category.name" class="form-control" id="product-name">
                                </div>


                                <div class="form-group">
                                    <label for="product-description" class="col-form-label">الوصف:</label>
                                    <textarea  v-model="category.description" class="form-control" id="product-description"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="button" class="btn btn-primary"  data-dismiss="modal" @click="AddCategory">حفظ التغير</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>

<script>
    export default {
       async mounted() {
            this.fetch_categories();
            if(this.isadmin) {
                const userres = await window.axios.get(this.baseurl + '/api/users');
                this.users = userres.data;
            }
        },
        data() {
             return {
                 categories: [],
                 category: {

                     name: '',
                     description: '',
                 },
                 oldcategory:{},
                 edit:false,
                 error:null,
                 user_id:'',
                 users:[],
                 alert:null,
                 isadmin:this.$isadmin == "1",
             }
        },
        methods:{
            async fetch_categories(){
                try{
                    const response  = await window.axios.get(this.baseurl + '/api/categories?user_id=' + this.user_id);
                    this.categories = response.data;
                }
                catch (e) {
                    console.log(e.message);
                }
            },
            async AddCategory(){

                if(!this.edit){
                    const data = new FormData();
                    data.append("file", this.file);
                    for ( var key in  this.category ) {
                        data.append(key, this.category[key]);
                    }

                    try{
                        var response = await axios({
                            method: 'post',
                            url: this.baseurl + '/api/categories',
                            data: data,
                        })
                        console.log(response.data.data);
                            this.categories.unshift(response.data.data);

                        this.ResetCategory()
                    }
                    catch (e) {
                        console.log(e.message);
                    }
                }
                else
                {
                    const data = new FormData();
                    data.append("_method", 'PATCH');
                    for (var key in  this.category) {
                        data.append(key, this.category[key]);
                    }

                    try {
                        var response = await axios({
                            method: 'post',
                            url: this.baseurl +  '/api/categories/' + this.category.id,
                            data: data,
                        })

                        this.oldcategory = (response.data.data);
                        this.ResetCategory()
                        this.alert = response.data.message;

                    } catch (e) {
                        this.ResetCategory()
                        this.error = e.response.data.message;
                    }

                }


            },
            ResetCategory(){
                this.Reload(this.category,this.oldcategory)

                this.category = {

                    name: '',
                    description: '',
                }

                this.edit=false;

                this.error = null;
                this.alert = null;

            },
            EditCategory(item){
                this.oldcategory = this.Clone(item);
                this.category = item;
                this.edit = true;
            },
            DeleteCategory(item){

                window.axios.delete(this.baseurl + "/api/categories/" + item.id).then(( reponse ) => {
                    this.categories.splice(this.categories.indexOf(item),1);
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
                    description:item.description,
                }
            },
            Reload(olditem,newitem){
                olditem.name = newitem.name;
                olditem.description = newitem.description;


            },


        }
    }
</script>
