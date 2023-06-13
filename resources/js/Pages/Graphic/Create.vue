<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WebLayout from '@/Layouts/WebLayout.vue';
import { Head } from '@inertiajs/vue3';
import Form from '@/Pages/Graphic/Form.vue';
</script>

<template>
    <Head title="Graficación" />

    <AuthenticatedLayout
        v-if="$page.props.auth.user"
    >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Graficación</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">Graficación autenticado!</div>
                    <Form/>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <WebLayout
        v-else
    >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Graficar un Atractor Caótico</h2>
        </template>
        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <Form/>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
