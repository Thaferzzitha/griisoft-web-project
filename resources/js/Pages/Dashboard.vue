<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import Swal from 'sweetalert2';
import Chart from '@/Components/Chart.vue';

const props = defineProps<{
  isSuperAdmin?: boolean;
}>();

const loading = ref(false);
const stats = ref({
  sprott: { total: 0, dates: {}},
  lorenz: { total: 0, dates: {}},
  chen: { total: 0, dates: {}},
  rossler: { total: 0, dates: {}}
});
const usersList = ref([]);
const startDate = ref('');
const endDate = ref('');
const selectedUser = ref('');

onMounted(async () => {
    resetDates();
    await fetchStats();
    if (props.isSuperAdmin) {
        await fetchUsersList();
    }
});

watch(selectedUser, async (newValue, oldValue) => {
    await fetchStats(newValue, startDate.value, endDate.value);
});
watch(startDate, async (newValue, oldValue) => {
    await fetchStats(selectedUser.value, newValue, endDate.value);
});
watch(endDate, async (newValue, oldValue) => {
    await fetchStats(selectedUser.value, startDate.value, newValue);
});

const fetchStats = async (user_id = '', start_date = '', end_date = '') => {
    loading.value = true;
    try {
        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };

        let url = `/api/graphic/stadistics`;

        const params = [];
        if (user_id !== '') {
            params.push(`user_id=${user_id}`);
        }
        if (start_date !== '') {
            params.push(`start_date=${start_date}`);
        }
        if (end_date !== '') {
            params.push(`end_date=${end_date}`);
        }

        if (params.length > 0) {
            url += `?${params.join('&')}`;
        }

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
};

const prepareValues = (type) => {
    const dates = Object.keys(type.dates);
    const values = dates.map(date => type.dates[date]);
    return values;
};

const prepareDates = (type) => {
    const dates = Object.keys(type.dates);
    return dates;
};
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
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-wrap justify-between">
                        <!-- User filter -->
                        <div v-if="$page.props.auth.user.is_super_admin" class="my-5 w-full sm:w-2/4 flex-row">
                            <label for="type" class="block w-full mb-1 dark:text-gray-300">Filtro por usuario</label>
                            <select :disabled="loading" v-model="selectedUser" name="user" id="user"
                                class="block w-full sm:w-11/12 text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option :value="''" :key="''">Todos los usuarios</option>
                                <option v-for="user in usersList" :value="user.value" :key="user.value">{{ user.text }}</option>
                            </select>
                        </div>
                        <!-- Date filters -->
                        <div :class="$page.props.auth.user.is_super_admin ? 'my-5 w-full sm:w-1/4 flex-row' : 'my-5 w-full sm:w-1/2 flex-row'">
                            <label for="type" class="block w-full mb-1 dark:text-gray-300">Filtro por fecha desde</label>
                            <input :disabled="loading" v-model="startDate" type="date" 
                                class="block w-full sm:w-11/12 text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        </div>
                        <div :class="$page.props.auth.user.is_super_admin ? 'my-5 w-full sm:w-1/4 flex-row' : 'my-5 w-full sm:w-1/2 flex-row'">
                            <label for="type" class="block w-full mb-1 dark:text-gray-300">Filtro por fecha hasta</label>
                            <input :disabled="loading" v-model="endDate" type="date" 
                                class="block w-full sm:w-11/12 text-base dark:bg-gray-500 dark:text-white border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="font-semibold text-md text-gray-800 dark:text-gray-200 leading-tight">Número de Gráficos por Tipo</h2>
                        <div class="flex flex-wrap mt-5" v-if="!loading">
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">sprott</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.sprott.total }}</h2>
                                </div>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">lorenz</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.lorenz.total }}</h2>
                                </div>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">chen</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.chen.total }}</h2>
                                </div>
                            </div>
                            <div class="w-full sm:w-1/2 md:w-1/4 lg:w-1/4 xl:w-1/4 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <h5 class="text-xl font-bold mb-2 text-center dark:text-gray-900 text-gray-100 capitalize">rossler</h5>
                                    <h2 class="text-3xl font-bold text-center dark:text-gray-900 text-gray-100">{{ stats.rossler.total }}</h2>
                                </div>
                            </div>
                        </div>
                        <div v-else class="my-10">Cargando...</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="font-semibold text-md text-gray-800 dark:text-gray-200 leading-tight">Gráficos estadisticos por tipo</h2>
                        <div class="flex flex-wrap mt-5" v-if="!loading">
                            <div class="w-full lg:w-1/2 xl:w-1/2 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <chart :dates="prepareDates(stats.sprott)" :values="prepareValues(stats.sprott)" name="Sprott" />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 xl:w-1/2 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <chart :dates="prepareDates(stats.lorenz)" :values="prepareValues(stats.lorenz)" name="Lorenz" />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 xl:w-1/2 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <chart :dates="prepareDates(stats.chen)" :values="prepareValues(stats.chen)" name="Chen" />
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 xl:w-1/2 p-5">
                                <div class="dark:bg-white bg-gray-800 p-4 flex flex-col justify-center stats-center shadow-md rounded-lg h-full">
                                    <chart :dates="prepareDates(stats.rossler)" :values="prepareValues(stats.rossler)" name="Rossler" />
                                </div>
                            </div>
                        </div>
                        <div v-else class="my-10">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
