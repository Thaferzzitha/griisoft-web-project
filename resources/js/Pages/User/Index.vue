<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import axios from 'axios';
import Swal from 'sweetalert2';

defineProps<{
    status?: string;
    users?: {};
    isSuperAdmin?: boolean;
}>();

const loading = ref(false);

const formatDate = (date) => {
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' };
    return new Date(date).toLocaleDateString('es-ES', options);
};
const redirect = (id) => {
    window.location.href = route('graphic.index', {user_id: id});
};

const makeSuperAdmin = (user) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `${user.name} será administrador del sistema`,
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
            axios.get(`/api/user/${user.id}/make-admin`, config)
                .then(() => {
                    Swal.fire({
                        title: 'Nuevo Administrador',
                        text: `${user.name} es un nuevo administrador del sistema`,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
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

const removeSuperAdmin = (user) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `${user.name} no será más administrador del sistema`,
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
            axios.get(`/api/user/${user.id}/remove-admin`, config)
                .then(() => {
                    Swal.fire({
                        title: 'Administrador removido',
                        text: `A ${user.name} se le removio el rol de administrador del sistema`,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
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
    <Head title="Usuarios" />
    <AuthenticatedLayout v-if="$page.props.auth.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuarios: Lista</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto">
                        <!-- Data Table -->
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Rol
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Fecha de Registro
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="!loading">
                                <tr v-for="user in users" :key="user.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="capitalize px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ user.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ user.roles.length ? 'Administrador' : 'Normal' }}
                                    </td>
                                    <td class="px-6 py-4 capitalize">
                                        {{ formatDate(user.created_at) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button
                                            class="my-5 mr-5 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
                                            @click="redirect(user.id)">
                                            Ver Historial
                                        </button>
                                        <button
                                            v-if="!user.roles.length"
                                            class="my-5 mr-5 bg-transparent hover:bg-indigo-500 text-indigo-700 font-semibold hover:text-white py-2 px-4 border border-indigo-500 hover:border-transparent rounded"
                                            @click="makeSuperAdmin(user)">
                                            Convertir en Administrador
                                        </button>
                                        <button
                                            v-if="user.roles.length"
                                            class="my-5 mr-5 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"
                                            @click="removeSuperAdmin(user)">
                                            Quitar Administrador
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-if="!loading && !users.length">
                                <tr>
                                    <th colspan="4" class="text-center p-5 dark:text-white">
                                        No existen usuarios.
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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
