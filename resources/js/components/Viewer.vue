<template>

    <div class="row  text-right">
        <div class="col-md-10">
            <router-view></router-view>
            <vue-progress-bar></vue-progress-bar>
        </div>

        <div class="col-md-2">
            <div class="list-group" v-if="!isadmin">
                <router-link :to="{name:'product'}" class="list-group-item list-group-item-action">المنتجات</router-link>
                <router-link :to="{name:'categories'}" class="list-group-item list-group-item-action">الاقسام</router-link>


                <router-link :to="{name:'pages'}" class="list-group-item list-group-item-action">الصفحات</router-link>
                <router-link :to="{name:'settings'}" class="list-group-item list-group-item-action">اعدادات الحساب</router-link>
            </div>

            <div class="list-group" v-else>
                <router-link :to="{name:'admin.product'}" class="list-group-item list-group-item-action">المنتجات</router-link>
                <router-link :to="{name:'admin.links'}" class="list-group-item list-group-item-action">الروابط</router-link>
                <router-link :to="{name:'settings'}" class="list-group-item list-group-item-action">اعدادات الحساب</router-link>
                <router-link :to="{name:'admin.users'}" class="list-group-item list-group-item-action">المستخدمين</router-link>
                <router-link :to="{name:'admin.categories'}" class="list-group-item list-group-item-action">الاقسام</router-link>


               <router-link :to="{name:'admin.pages'}" class="list-group-item list-group-item-action">الصفحات</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                isadmin:this.$isadmin == "1"
            }
        }
    }
</script>
