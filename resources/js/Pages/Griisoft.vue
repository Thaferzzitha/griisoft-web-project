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
    <Head title="Griisoft" />

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
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 my-10">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Enfoque de cifrado de objetos JSON utilizando sincronización caótica a partir del análisis de un conjunto de atractores IDIPI-28</h1>
                    <p class="dark:text-gray-300 text-lg text-justify mb-8">Resumen. En la actualidad en mayor o menor medida los productos software desarrollados se encuentran expuestos al Internet. Internamente estos productos están conformados por componentes software los cuales, muchos de ellos, se intercomunican a través del Internet, enviando y recibiendo información. Un formato común para el intercambio de información entre estos componentes es el formato JSON, Sin bien existen algunos enfoque para asegurar la información en este formato, se ha observado algunas amenazas que implican el acceso no autorizado a la información contenida en los objetos JSON. Con el fin de ofrecer una alternativa al actual enfoque de cifrado de objetos JSON en la presente investigación se propone el desarrollo de un enfoque de cifrado de objetos JSON utilizando sincronización caótica a partir de la selección de un conjunto de atractores. El enfoque a desarrollar se pretende pueda ser utilizado en el ámbito académico por docentes e investigadores, así como por profesionales en la industria de software.</p>
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 my-10">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Sincronización Caótica</h1>
                    <p class="dark:text-gray-300 text-lg text-justify mb-8">La sincronización caótica es un fenómeno en el que dos o más sistemas caóticos pueden ajustar su dinámica para volverse idénticos o similares. Esto puede suceder cuando los sistemas están acoplados por alguna forma de interacción o comunicación, como una fuerza impulsora común, una señal de retroalimentación o un entorno compartido. La sincronización caótica tiene aplicaciones en varios campos, como la comunicación segura, las redes neuronales y las redes complejas.</p>
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 my-10">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Atractor de Rossler</h1>
                    <p class="dark:text-gray-300 text-lg text-justify mb-8">El atractor de Rössler describe el comportamiento de un sistema dinámico de tres ecuaciones diferenciales ordinarias no lineales. Fue estudiado originalmente por Otto Rössler en la década de 1970 y exhibe dinámicas caóticas y propiedades fractales. El atractor de Rössler se puede utilizar para modelar el equilibrio en reacciones químicas y otros fenómenos. Las ecuaciones que definen el atractor de Rössler son: donde A, B y C son los parametros de equilibrio. El atractor de Rössler tiene forma de espiral que se tuerce y gira en tres dimensiones. Tiene algunas similitudes con el atractor de Lorenz, pero es más simple y tiene solo una variedad.</p>
                    <img src="/assets/images/rossler-atractor.jpeg" alt="" class="dark:bg-transparent bg-gray-200 p-2 rounded mx-auto">
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 my-10">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Atractor de Lorenz</h1>
                    <p class="dark:text-gray-300 text-lg text-justify mb-8">El atractor de Lorenz es un modelo matemático de un sistema dinámico (caótico) que exhibe una dependencia sensible de las condiciones iniciales. Fue introducido por primera vez por Edward Lorenz en 1963 como una representación simplificada de la convección atmosférica. El atractor de Lorenz consta de tres ecuaciones diferenciales acopladas que describen la evolución de tres variables: x, y y z. Las ecuaciones tienen tres parámetros: sigma, rho y beta, que controlan la forma y el comportamiento del atractor. El atractor de Lorenz es uno de los ejemplos más famosos de un atractor extraño, un conjunto de puntos en el espacio de fase que tiene una estructura fractal y atrae trayectorias cercanas</p>
                    <img src="/assets/images/lorenz-atractor.jpeg" alt="" class="dark:bg-transparent bg-gray-200 p-2 rounded mx-auto">                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 my-10">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Atractor de Chen</h1>
                    <p class="dark:text-gray-300 text-lg text-justify mb-8">El atractor de Chen es un tipo de atractor de desplazamiento múltiple propuesto por Guanrong Chen, un matemático chino e investigador del caos. El atractor de Chen es un sistema dinámico tridimensional que exhibe un comportamiento caótico y tiene una estructura geométrica compleja. El atractor de Chen se puede describir mediante un sistema de tres ecuaciones diferenciales ordinarias no lineales con tres parámetros. El atractor de Chen tiene algunas similitudes con el atractor de Lorenz, pero también algunas diferencias, como la forma y el número de puntos de equilibrio.</p>
                    <img src="/assets/images/chen-atractor.jpeg" alt="" class="dark:bg-transparent bg-gray-200 p-2 rounded mx-auto">
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-8 my-10">
                    <h1 class="dark:text-white text-4xl font-bold text-center mb-4">Atractor de Sprott</h1>
                    <p class="dark:text-gray-300 text-lg text-justify mb-8">El atractor de Sprott es un tipo de atractor caótico que tiene forma de mariposa y dimensión fraccionaria. Fue descubierto por Julien C. Sprott, físico e investigador del caos de la Universidad de Wisconsin-Madison. El atractor de Sprott se puede generar mediante un mapa cuadrático tridimensional con términos no lineales. El atractor de Sprott tiene propiedades interesantes como la multiestabilidad, la coexistencia de múltiples atractores y la sensibilidad a las condiciones iniciales. El atractor Sprott se puede utilizar para modelar fenómenos complejos como redes neuronales, sistemas láser y percepción.</p>
                    <img src="/assets/images/sprott-atractor.jpeg" alt="" class="dark:bg-transparent bg-gray-200 p-2 rounded mx-auto">
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