<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import Rossler from "./Partials/Rossler.vue";
import Lorenz from "./Partials/Lorenz.vue";
import Chen from "./Partials/Chen.vue";
import Sprott from "./Partials/Sprott.vue";
import Swal from 'sweetalert2';

defineProps<{
    status?: string;
}>();

const items = ref({});
const usersList = ref([]);
const atractorData = ref({});
const showAtractor = ref(false);
const loading = ref(false);
const selectedType = ref('');
const typedTitle = ref('');
const selectedUser = ref('');

onMounted(async () => {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('user_id')) {
        selectedUser.value = urlParams.get('user_id');
    }
    await fetchUsersList();
    await fetchData('','',selectedUser.value);
});

watch(selectedUser, async (newValue, oldValue) => {
    await fetchData(selectedType.value, typedTitle.value, newValue);
});
watch(selectedType, async (newValue, oldValue) => {
    await fetchData(newValue, typedTitle.value);
});
watch(typedTitle, async (newValue, oldValue) => {
    await fetchData(selectedType.value, newValue);
});

const fetchData = async (type = '', title = '', userId = '') => {
    loading.value = true;
    try {
        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };

        let url = `/api/graphic/list?type=${type}`;

        if (title.length !== 0) {
            url = `${url}&title=${title}`;
        }

        if (userId.length !== 0) {
            url = `${url}&user_id=${userId}`;
        }

        const response = await axios.get(url, config);
        items.value = response.data;
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'Se produjo un error en la operación ' + error,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
        })
    }
    loading.value = false;
};

const fetchUsersList = async () => {
    loading.value = true;
    try {
        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };

        const response = await axios.get('/api/user/list-users', config);
        response.data.forEach(item => {
            const newUser = {
                value: item.id,
                text: item.name
            };
            usersList.value.push(newUser);  
        });
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'Se produjo un error en la operación ' + error,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
        })
    }
    loading.value = false;
};

const formatDate = (date) => {
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' };
    return new Date(date).toLocaleDateString('es-ES', options);
};

const redirect = (action, id) => {
    if (action == 'edit') {
        window.location.href = route('graphic.edit', id);
    }
};

const showAtractorModal = (item) => {
    atractorData.value = item;
    showAtractor.value = true;
};

const closeModal = () => {
    showAtractor.value = false;
};

const onDelete = (id) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará el gráfico permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const token = localStorage.getItem("access_token");

            const config = {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            };
            axios.delete(`/api/graphic/${id}`, config)
                .then(() => {
                    Swal.fire({
                        title: 'Registro eliminado',
                        text: 'El registro se ha eliminado exitosamente.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    fetchData();
                })
                .catch((error) => {
                    Swal.fire({
                        title: 'Error',
                        text: 'Se produjo un error en la operación ' + error.response.data.errors.title,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    })
                });
        }
    });
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
                        <!-- User filter -->
                        <div v-if="$page.props.auth.user.is_super_admin" class="my-5">
                            <label for="type" class="block w-11/12 mx-auto mb-1 dark:text-gray-300">Filtro por usuario</label>
                            <select v-model="selectedUser" name="user" id="user"
                                class="block w-11/12 mx-auto text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option v-for="user in usersList" :value="user.value" :key="user.value">{{ user.text }}
                                </option>
                            </select>
                        </div>
                        <!-- Type filter -->
                        <div class="my-5">
                            <label for="type" class="block w-11/12 mx-auto mb-1 dark:text-gray-300">Filtro por tipo</label>
                            <select v-model="selectedType" name="type" id="type"
                                class="block w-11/12 mx-auto text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="" selected>Todos los atractores...</option>
                                <option value="rossler">Atractor de Rossler</option>
                                <option value="lorenz">Atractor de Lorenz</option>
                                <option value="chen">Atractor de Chen</option>
                                <option value="sprott">Atractor de Sprott</option>
                            </select>
                        </div>
                        <!-- Title Filter -->
                        <div class="my-5 ">
                            <label for="type" class="block w-11/12 mx-auto mb-1 dark:text-gray-300">Filtro por
                                título</label>
                            <input v-model="typedTitle" type="text" id="title"
                                class="block w-11/12 mx-auto border-gray-300 border rounded-md p-2 mb-1" />
                        </div>
                        <!-- Data Table -->
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Título
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tipo
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Fecha de Última Modificación
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="!loading">
                                <tr v-for="item in items" :key="item.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="capitalize px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.title }}
                                    </th>
                                    <td class="px-6 py-4 capitalize">
                                        {{ item.type }}
                                    </td>
                                    <th scope="row" class="px-6 py-4 capitalize">
                                        {{ formatDate(item.updated_at) }}
                                    </th>
                                    <td class="px-6 py-4">
                                        <button
                                            class="my-5 mr-5 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                                            @click="showAtractorModal(item)">
                                            Ver
                                        </button>
                                        <button
                                            class="my-5 mr-5 bg-transparent hover:bg-indigo-500 text-indigo-700 font-semibold hover:text-white py-2 px-4 border border-indigo-500 hover:border-transparent rounded"
                                            @click="redirect('edit', item.id)">
                                            Editar
                                        </button>
                                        <button
                                            class="my-5 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"
                                            @click="onDelete(item.id)">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-if="!loading && !items.length">
                                <tr>
                                    <th colspan="4" class="text-center p-5 dark:text-white">
                                        No existen gráficos.
                                    </th>
                                </tr>
                            </tbody>
                            <tbody v-if="loading">
                                <tr>
                                    <th colspan="4" class="text-center p-5 dark:text-white">
                                        Cargando...
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Show Graphic Modal -->
                        <Modal :show="showAtractor" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Atractor de {{ atractorData.type }} creando el {{ formatDate(atractorData.created_at) }}
                                </h2>

                                <Rossler v-if="atractorData.type == 'rossler'" :is-read-only="true"
                                    :atractor="atractorData"></Rossler>
                                <Lorenz v-if="atractorData.type == 'lorenz'" :is-read-only="true" :atractor="atractorData">
                                </Lorenz>
                                <Chen v-if="atractorData.type == 'chen'" :is-read-only="true" :atractor="atractorData">
                                </Chen>
                                <Sprott v-if="atractorData.type == 'sprott'" :is-read-only="true" :atractor="atractorData">
                                </Sprott>

                                <div class="mt-6 flex justify-end">
                                    <SecondaryButton @click="closeModal"> Cerrar </SecondaryButton>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
        </div>
    </div>
</AuthenticatedLayout></template>
