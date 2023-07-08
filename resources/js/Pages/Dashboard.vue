<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import Swal from 'sweetalert2';

const loading = ref(false);
const stats = ref({});
const usersList = ref([]);
const startDate = ref();
const endDate = ref();
const selectedUser = ref('');

onMounted(async () => {
    resetDates();
    await fetchStats();
    await fetchUsersList();
});

const fetchStats = async () => {
    loading.value = true;
    try {
        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };

        let url = `/api/graphic/stadistics`;

        const response = await axios.get(url, config);
        stats.value = response.data;
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

const resetDates = () => {
    const currentDate = new Date();
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    
    startDate.value = firstDay.toISOString().slice(0, 10);
    endDate.value = lastDay.toISOString().slice(0, 10);
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- User filter -->
                        <div v-if="$page.props.auth.user.is_super_admin" class="my-5">
                            <label for="type" class="block w-full mx-auto mb-1 dark:text-gray-300">Filtro por usuario</label>
                            <select v-model="selectedUser" name="user" id="user"
                                class="block w-full mx-auto text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option v-for="user in usersList" :value="user.value" :key="user.value">{{ user.text }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="font-semibold text-md text-gray-800 dark:text-gray-200 leading-tight">Número de Gráficos por Tipo</h2>
                        <div class="flex flex-wrap mt-5">
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 px-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">sprott</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.sprott }}</h2>
                                </div>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 px-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">lorenz</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.lorenz }}</h2>
                                </div>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 px-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">chen</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.chen }}</h2>
                                </div>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 px-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">rossler</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.rossler }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
