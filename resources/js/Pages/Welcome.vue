<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <Head title="Bienvenido/a" />

    <div
        class="relative sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="top-0 right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Iniciar Sesión</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Registrar</Link
                >
            </template>
        </div>

        <div class="container mx-auto py-8">
            <ApplicationLogo
                class="flex justify-center mx-auto w-auto fill-current h-40 mb-4"
            />

            <div class="w-11/12 mx-auto mb-8">
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Atractores Caóticos</h1>
                    
                    <p class="dark:text-gray-300 text-lg text-center mb-8">Los atractores caóticos son sistemas dinámicos no lineales que exhiben comportamientos altamente sensibles a las condiciones iniciales. Descubre la belleza y complejidad de estos atractores mediante nuestra herramienta de graficación.</p>

                    <div class="flex justify-center mb-6">
                        <a href="/graphic/create" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Grafica un atractor!</a>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap justify-center mb-6 mx-auto w-11/12">
                <div class="w-full md:w-1/2">
                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 mb-4 md:mb-0 md:mr-2">
                        <h2 class="dark:text-white text-2xl font-bold mb-4">Documentación del API de Graficación</h2>
                        <p class="dark:text-gray-300 mb-4">Explora nuestra documentación para obtener información detallada sobre cómo utilizar nuestro API de graficación.</p>
                        <a href="/api/documentation" class="text-blue-500 hover:underline">Ver documentación</a>
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 md:ml-2">
                        <h2 class="dark:text-white text-2xl font-bold mb-4">GrIISoft</h2>
                        <p class="dark:text-gray-300 mb-4"> GrIISoft  es el Grupo de Investigación en Ingeniería de Software de la Facultad de Informática y Electrónica de la ESPOCH.</p>
                        <a href="/griisoft" class="text-blue-500 hover:underline">Conoce mas aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
