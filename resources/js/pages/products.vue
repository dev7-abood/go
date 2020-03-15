<template>
    <div>
        <h2 class="text-right">قائمة المنتجات</h2>
        <div class="form-group mt-4">

            <select class="form-control float-right text-right  d-inline-block col-md-4 m-2" @change="fetch_categories"
                    v-model="user_id" v-if="isadmin">
                <option value="">اختر المستخدم</option>
                <option v-for="user in users" :value="user.id">{{ user.name }}</option>
            </select>

            <select class="form-control float-right text-right d-inline-block col-md-4 m-2" @change="ListProduct"
                    v-model="category_id">
                <option value="">اختر القسم</option>
                <option v-for="cat in categories" :value="cat.id">{{ cat.name }}</option>
            </select>
            <div class="clearfix"></div>
        </div>
        <div class="form-group mt-4">
            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#ModelAddProduct">اضف منتج
            </button>
            <div class="clearfix"></div>
        </div>

        <div class="form-group mt-4 table-responsive">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">اسم القسم</th>
                    <th scope="col">اسم الصفحة</th>
                    <th scope="col">الصورة</th>
                    <th scope="col">اسم المنتج</th>
                    <th scope="col">الوصف</th>
                    <th scope="col">الثمن</th>
                    <th scope="col">الثمن السابق</th>
                    <th scope="col">الكمية</th>
                    <th scope="col">الاحداث</th>
                </tr>
                </thead>
                <tbody>

                <td>
                    <th scope="row">-</th>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>{{product.name}}</td>
                    <td>{{product.description}}</td>
                    <td>{{product.price}}</td>
                    <td>{{product.prv_price}}</td>
                    <td>{{product.qte}}</td>
                    <td>***</td>

                <tr v-for="(row,index) in products">
                    <th scope="row">{{ index+1 }}</th>
                    <td>{{ row.category_name }}</td>
                    <td>{{ row.page_name }}</td>
                    <td>
                        <div :id="'carousel' + index" class="carousel slide" data-ride="carousel"
                             style="overflow: hidden;width: 100px;display: inline-block;">
                            <div class="carousel-inner">
                                <div v-for="(image_url,index) in row.image_urls"
                                     :class="'carousel-item ' +  (index == 0 ? 'active':'')">
                                    <img :src="baseurl + '/showfile/' + image_url" style="width: 100px;height: 100px;"/>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{ row.name }}</td>
                    <td>{{ row.description }}</td>
                    <td>{{ row.price }}</td>
                    <td>{{ row.prv_price }}</td>
                    <td>{{ row.qte }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm dropdown-toggle"
                                type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            الاحداث
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item" @click="EditProduct(row)" data-toggle="modal"
                                    data-target="#ModelAddProduct">تعديل
                            </button>
                            <button class="dropdown-item" @click="DeleteProduct(row)">حدف</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ModelAddProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">انشاء منتج</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">

                            <div id="preview" class="mb-2 d-flex align-items-center align-content-center"
                                 style="max-height: 125px;padding: 0 0 25px 0px !important;width: 100%;overflow: overlay;height: 100%;">
                                <div class="content-close" v-if="urls" v-for="url in urls">
                                    <span class="text-danger"  @click="removeFile( urls.indexOf(url) )" style="font-size : 30px;position: relative;top: -18px;left : 70px ;z-index: 10;cursor: pointer">x</span>
                                    <img
                                         :src=" (url.startsWith('blob')?'':baseurl + '/showfile/') + url"
                                         class="d-inline-block" style="width: 75px; height: 75px;"/>
                                </div>
                            </div>

                            <label for="product-image" class="btn btn-sm btn-danger">رفع الصورة</label>
                            <input id="product-image" multiple type="file"  @change="onFileChange" class="d-none"/>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">  <label for="product-page" class="col-form-label">الصفحات:</label>
                                    <select id="product-page" class="form-control"
                                            v-model="product.page_id">
                                        <option>All Pages</option>
                                        <option v-for="page in pages" :value="page.id" >{{ page.name }}</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                   <label for="product-category" class="col-form-label">الاقسام:</label>
                                    <select id="product-category" class="form-control"
                                            v-model="product.category_id">
                                        <option>All</option>
                                        <option v-for="cat in categories"  required  :value="cat.id"  >{{ cat.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="product-description" class="col-form-label">الوصف:</label>
                                    <textarea v-model="product.description" class="form-control"
                                              id="product-description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="product-price" class="col-form-label">الثمن:</label>
                                    <input type="text" v-model="product.price" class="form-control" id="product-price">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="product-name" class="col-form-label">اسم المنتج:</label>
                                    <input type="text" v-model="product.name" class="form-control" id="product-name">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="product-qte" class="col-form-label">الكمية:</label>
                                    <input type="text" v-model="product.qte" class="form-control" id="product-qte">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="product-prv_price" class="col-form-label">السعر السابق:</label>
                                    <input type="text" v-model="product.prv_price" class="form-control"
                                           id="product-prv_price">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product-color"  class="col-form-label">اللون:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label for="product-color" class="btn btn-outline-secondary" @click="AddColor" type="button" >+</label>
                                        </div>
                                        <input  type="text" v-model="product.color" class="form-control" id="product-color">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="product-taille" class="col-form-label">المقاس:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label for="product-taille" class="btn btn-outline-secondary" @click="AddTaille" type="button">+</label>
                                        </div>
                                        <input type="text" v-model="product.taille" class="form-control"
                                               id="product-taille">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="ResetProduct">
                            الغاء
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="AddProduct">حفظ
                            التغير
                        </button>
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
            this.ListProduct();
            if (this.isadmin) {
                const userres = await window.axios.get(this.baseurl + '/api/users');
                this.users = userres.data;
            }
        },
        data() {
            return {
                categories: [],
                pages: [],
                products: [],
                users: [],
                category_id: '',
                page_id: '',
                user_id: '',
                urls: [],
                product: {
                    name: '',
                    description: '',
                    category_id: '',
                    page_id: [],
                    price: 0,
                    qte: 0,
                    color: '',
                    taille: '',
                    prv_price: 0
                },
                oldproduct: {},
                files: [],
                isadmin: this.$isadmin == "1",
                edit: false
            }
        },
        methods: {
            async fetch_categories() {
                try {
                    const response = await window.axios.get(this.baseurl + '/api/categories?user_id=' + this.user_id);
                    this.categories = response.data;
                } catch (e) {
                    console.log(e.message);
                }
                this.fetch_pages();
            },
            async fetch_pages() {
                try {
                    const response = await window.axios.get(this.baseurl + '/api/pages?user_id=' + this.user_id);
                    this.pages = response.data;
                } catch (e) {
                    console.log(e.message);
                }
            },
            ListProduct() {
                try {
                    var data = {};
                    data = {category_id: this.category_id, page_id: this.page_id, user_id: this.user_id};
                    axios({
                        method: 'post',
                        url: this.baseurl + '/api/listproduct?nourl',
                        data: data,
                    }).then(response => {
                        this.products = response.data;
                        setTimeout(function () {
                            $('.carousel').carousel()
                        }, 2000);
                    })
                } catch (e) {
                    console.log(e.message);
                }
            },
            AddTaille(){
                this.product.taille += ",";
            },
            AddColor(){
                this.product.color += ",";
                this.product.color.background = '#000';
            },
            onFileChange(e) {
                Array.prototype.push.apply(this.files, e.target.files);
                let file
                if (this.product.image_urls == undefined || Array.prototype.values(this.product.image_urls).length == 0) {
                    this.urls = [];
                } else {
                    this.urls = this.product.image_urls.slice(0);
                }
                for (file of this.files) {
                    this.urls.push(URL.createObjectURL(file));
                }
            },
            removeFile(index) {
                this.urls.splice(index, 1);
                if (this.product.image_urls == undefined || Array.prototype.values(this.product.image_urls).length == 0) {
                    this.files.splice(index, 1);
                } else {
                    this.files.splice(Array.prototype.values(this.product.image_urls).length + index, 1);
                }
            },
            async AddProduct() {
                this.$Progress.start()
                if (!this.edit) {
                    const data = new FormData();
                    let file
                    for (file of this.files) {
                        data.append("files[]", file);
                    }
                    data.append("user_id", this.user_id);
                    data.append("urls", this.urls);
                    for (var key in  this.product) {
                        data.append(key, this.product[key]);
                    }
                    try {
                        var response = await axios({
                            method: 'post',
                            url: this.baseurl + '/api/products?nourl',
                            data: data,
                        })
                        console.log(response.data.data);
                        this.products.unshift(response.data.data);
                        this.ResetProduct()
                    } catch (e) {
                        console.log(e.message);
                    }
                } else {
                    const data = new FormData();
                    data.append("_method", 'PATCH');
                    let file
                    for (file of this.files) {
                        data.append("files[]", file);
                    }
                    for (var key in  this.product) {
                        if (key == 'image_urls') {
                            let turls = this.urls;
                            this.product['image_urls'] = this.product['image_urls'].filter((el) => turls.includes(el));
                            data.append(key, this.product[key]);
                        } else
                            data.append(key, this.product[key]);
                    }
                    try {
                        var response = await axios({
                            method: 'post',
                            url: this.baseurl + '/api/products/' + this.product.id + '?nourl',
                            data: data,
                        })
                        this.oldproduct = (response.data.data);
                    } catch (e) {
                        console.log(e.message);
                    }
                    this.ResetProduct()
                }
            },
            async EditProduct(item) {
                this.oldproduct = this.Clone(item);
                this.product = item;
                this.urls = (item.image_urls);
                this.edit = true;
            },
            DeleteProduct(item) {
                window.axios.delete(this.baseurl + "/api/products/" + item.id).then(({reponse}) => {
                    this.products.splice(this.products.indexOf(item), 1);
                }).catch((error,) => {
                    console.log(error.response.data.message);
                });
            }
            ,
            Clone(item) {
                let obj = {}, key
                for (key in item)
                    obj[key] = item[key];
                return obj;
            },
            Reload(olditem, newitem) {
                olditem.name = newitem.name;
                olditem.description = newitem.description;
                olditem.category_id = newitem.category_id;
                olditem.page_id = newitem.page_id;
                olditem.category_name = newitem.category_name,
                    olditem.page_name = newitem.page_name,
                    olditem.price = newitem.price;
                olditem.id = newitem.id;
                olditem.qte = newitem.qte;
                olditem.taille = newitem.taille;
                olditem.color = newitem.color;
                olditem.image_urls = newitem.image_urls;
                olditem.prv_price = newitem.prv_price;
            },
            ResetProduct() {
                this.Reload(this.product, this.oldproduct)
                this.product = {
                    name: '',
                    description: '',
                    category_id: '',
                    page_id: '',
                    price: 0,
                    prv_price: 0,
                    taille: '',
                    color: '',
                    qte: 0
                }
                this.urls = [];
                this.files = [];
                this.edit = false;
            }
        }
    }
</script>
