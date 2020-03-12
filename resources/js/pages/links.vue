<template>
    <div>
        <h2>قائمة الروابط</h2>

        <select  class="form-control float-right text-right  d-inline-block col-md-4 m-2" @change="getLinks" v-model="user_id" v-if="isadmin">
            <option  value="">اختر المستخدم</option>
            <option   v-for="user in users" :value="user.id">{{ user.name }}</option>
        </select>


        <div class="form-group mt-4 table-responsive">
            <h3>الروابط بالاقسام</h3>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">الرابط</th>
                    <th scope="col">اسم القسم</th>
                    <th scope="col">النوع</th>
                    <th scope="col">#</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="link in links" v-if="link.category_id != null">
                    <td><a :href="link.link_url" target="_blank">{{ link.link_url}}</a></td>
                    <td>{{ link.category_id != null ?'قسم':'صفحة'}} </td>
                    <td>{{ link.category_id != null ?link.category_name:link.page_name}} </td>
                    <th scope="row">{{ link.id }}</th>
                </tr>
                </tbody>
            </table>
            <h3>الروابط بالصفحات</h3>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">الرابط</th>
                    <th scope="col">اسم القسم</th>
                    <th scope="col">النوع</th>
                    <th scope="col">#</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="link in links" v-if="link.category_id == null">
                    <td><a :href="link.link_url" target="_blank">{{ link.link_url}}</a></td>
                    <td>{{ link.category_id != null ?'قسم':'صفحة'}} </td>
                    <td>{{ link.category_id != null ?link.category_name:link.page_name}} </td>
                    <th scope="row">{{ link.id }}</th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
       async mounted() {
           this.getLinks();

            if(this.isadmin) {
                const userres = await window.axios.get(this.baseurl + '/api/users');
                this.users = userres.data;
            }
        },
        data() {
            return {
                links:[],
                users:[],
                user_id:'',
                isadmin:this.$isadmin == "1",
            }
        },
        methods:{
            async getLinks(){
                try{

                    const response  = await window.axios.get(this.baseurl + '/api/links?user_id=' + this.user_id );
                    console.log(this.baseurl + '/api/links?user_id=' + this.user_id);
                    this.links = response.data.data;

                }
                catch (e) {
                    console.log(e.message);
                }
            },
        }
    }
</script>
