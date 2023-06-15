<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Form from '@/Pages/Graphic/Form.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const atractor = ref(null);

onMounted(async () => {
    try {
        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };
        const graphId = route().params.graphic;

        const response = await axios.get(`/api/graphic/${graphId}`, config);
        atractor.value = response.data;
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'Se produjo un error en la operación ' + error,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
        })
    }
});
</script>

<template>
    <Head title="Graficación" />

    <AuthenticatedLayout v-if="$page.props.auth.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Graficación: Modificar Atractor
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <Form v-if="atractor" :atractor="atractor"/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>