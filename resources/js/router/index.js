import { createRouter, createWebHistory } from "vue-router";
import ProductList from "../components/products/ProductList.vue";
import NotFound from "../components/NotFound.vue";
import AddProduct from "../components/products/AddProduct.vue";

const routes = [
    {
        path: '/',
        name: 'products.index',
        component: ProductList
    },
    {
        path: '/products/create',
        name: 'products.create',
        component: AddProduct
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'notfound',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router