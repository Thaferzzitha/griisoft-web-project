<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import Rossler from "./Partials/Rossler.vue";

defineProps<{
    status?: string;
}>();

const fetchData = async () => {
    try {
        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };

        const response = await axios.get('/api/graphic/list', config);

        return response.data;
    } catch (error) {
        console.error(error);
        return [];
    }
};

const items = ref({});
const atractorData = ref({});
const showAtractor = ref(false);


onMounted(async () => {
    items.value = await fetchData(); // Almacenar los datos devueltos en la variable
});

const formatDate = (date) => {
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' };
    return new Date(date).toLocaleDateString('es-ES', options);
};

const redirectToCreate = () => {
    window.location.href = route('graphic.create');
};

const showAtractorModal = (item) => {
    showAtractor.value = true;
    atractorData.value = item;
};

const closeModal = () => {
    showAtractor.value = false;
};
</script>

<template>
    <Head title="Graficación" />
    <AuthenticatedLayout v-if="$page.props.auth.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Graficación: Historial</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Título
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Fecha de Creación
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tipo
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in items" :key="item.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="capitalize px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.title }}
                                    </th>
                                    <th scope="row" class="px-6 py-4 capitalize">
                                        {{ formatDate(item.created_at) }}
                                    </th>
                                    <td class="px-6 py-4 capitalize">
                                        {{ item.type }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button
                                            class="mr-5 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                                            @click="showAtractorModal(item)">
                                            Ver
                                        </button>
                                        <button
                                            class="mr-5 bg-transparent hover:bg-indigo-500 text-indigo-700 font-semibold hover:text-white py-2 px-4 border border-indigo-500 hover:border-transparent rounded">
                                            Editar
                                        </button>
                                        <button
                                            class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Modal :show="showAtractor" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Atractor de {{ atractorData.type }} creando el {{ formatDate(atractorData.created_at) }}
                                </h2>

                                <Rossler :is-read-only="true" :atractor="atractorData"></Rossler>

                                <div class="mt-6 flex justify-end">
                                    <SecondaryButton @click="closeModal"> Cerrar </SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
