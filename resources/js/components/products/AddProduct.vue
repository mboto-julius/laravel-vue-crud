<template>
    <section>
        <div class="products__create ">
            <div class="products__create__titlebar dflex justify-content-between align-items-center">
                <div class="products__create__titlebar--item">
                    <h1 class="my-1">Add Product</h1>
                </div>
                <div class="products__create__titlebar--item">
                    <router-link to="/" class="btn btn-secondary ml-1" >
                        Back
                    </router-link>
                </div>
            </div>
            <div class="products__create__cardWrapper mt-2">
                <div class="products__create__main">
                    <div class="products__create__main--addInfo card py-2 px-2 bg-white">
                        <p class="mb-1">Name</p>
                        <input type="text" class="input" v-model="data.name">
                        <small style="color: red;" v-if="errors.name">{{ errors.name }}</small>

                        <p class="my-1">Description (optional)</p>
                        <textarea cols="10" rows="5" class="textarea" v-model="data.description"></textarea>
                        <small style="color: red;" v-if="errors.description">{{ errors.description }}</small>

                        <div class="products__create__main--media--images mt-2">
                            <ul class="products__create__main--media--images--list list-unstyled">
                                <li class="products__create__main--media--images--item">
                                    <div class="products__create__main--media--images--item--imgWrapper">
                                        <img :src="getImage()" class="products__create__main--media--images--item--img" alt="product_image" />  
                                    </div>
                                </li>
                                <!-- upload image small -->
                                <li class="products__create__main--media--images--item">
                                    <form class="products__create__main--media--images--item--form">
                                        <label class="products__create__main--media--images--item--form--label" for="myfile">Add Image</label>
                                        <input class="products__create__main--media--images--item--form--input" type="file" id="myfile"
                                            @change="handleFileChange">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="products__create__sidebar">
                    <!-- Product Organization -->
                    <div class="card py-2 px-2 bg-white">
                        <!-- Product unit -->
                        <div class="my-3">
                            <p>Product type</p>
                            <input type="text" class="input" v-model="data.type">
                        </div>
                        <hr>
                        <!-- Product invrntory -->
                        <div class="my-3">
                            <p>Inventory</p>
                            <input type="text" class="input" v-model="data.quantity">
                        </div>
                        <hr>
                        <!-- Product Price -->
                        <div class="my-3">
                            <p>Price</p>
                            <input type="text" class="input" v-model="data.price">
                        </div>
                    </div>

                </div>
            </div>
            <!-- Footer Bar -->
            <div class="dflex justify-content-between align-items-center my-3">
                <p ></p>
                <button @click="addProduct" class="btn btn-secondary">Save</button>
            </div>
        </div>
    </section>
</template>

<script setup>

import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

const data = reactive({
    name: "",
    description: "",
    image: "", 
    type: "",
    quantity: "",
    price: ""
})

const router = useRouter();

let errors = ref([])

const getImage = () => {
    let image = "/upload/no-image.jpg"
    if(data.image) {
        if(data.image.indexOf("base64") != -1){
            image = data.image
        }else{
            image = "/upload/" + data.image
        }
    }
    return image
}

const handleFileChange = (e) => {
    let file = e.target.files[0]
    let reader = new FileReader()
    reader.onloadend = (file) => {
        data.image = reader.result
    }
    reader.readAsDataURL(file)
}

const addProduct = () => {
    axios.post('/api/products', data)
    .then((response) => {
        router.push('/')
        toast.fire({ icon: "success", title: "Product added successfully" })
    })
    .catch((error) => {
        if(error.response.status === 422){
            errors.value = error.response.data.errors
        }
    })
}

</script>