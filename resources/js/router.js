import Vue from  'vue'
import VueRouter from 'vue-router'

Vue.use( VueRouter )

let baseurl =  document.querySelector("meta[name='baseurl']").getAttribute('content');
export default new VueRouter({
    base: baseurl,
    mode:'history',
   routes:[
       {
           path:'/',
           name:'home',
           component:require('@/pages/products').default
       },
       {
           path:'/products',
           name:'product',
           component:require('@/pages/products').default
       },
       {
           path:'/categories',
           name:'categories',
           component:require('@/pages/categories').default
       },
       {
           path:'/pages',
           name:'pages',
           component:require('@/pages/pages').default
       },
       {
           path:'/links',
           name:'links',
           component:require('@/pages/links').default
       },
       {
           path:'/settings',
           name:'settings',
           component:require('@/pages/settings').default
       }
       ,
       {
           path:'/admin/products',
           name:'admin.product',
           component:require('@/pages/products').default
       },
       {
           path:'/admin/links',
           name:'admin.links',
           component:require('@/pages/links').default
       },
       {
           path:'/admin/settings',
           name:'admin.settings',
           component:require('@/pages/settings').default
       },
       {
           path:'/admin/users',
           name:'admin.users',
           component:require('@/pages/users').default
       },
       {
           path:'/admin/categories',
           name:'admin.categories',
           component:require('@/pages/categories').default
       },
       {
           path:'/admin/pages',
           name:'admin.pages',
           component:require('@/pages/pages').default
       }
   ]
});