<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
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
</script>

<template>
    <Head title="Graficación" />
    <AuthenticatedLayout
        v-if="$page.props.auth.user"
    >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Graficación: Historial</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
