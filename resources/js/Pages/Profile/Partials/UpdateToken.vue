<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Swal, { SweetAlertOptions, SweetAlertResult } from "sweetalert2";
import axios from 'axios';
import { ref } from 'vue';

const showToken = ref(false);
const newToken = ref('');

const updateToken = async () => {
    try {
        const loadingAlertOptions: SweetAlertOptions = {
            title: 'Cargando...',
            allowOutsideClick: false,
            didOpen: () => {
            Swal.showLoading();
            },
        };
        const loadingAlert: SweetAlertResult = Swal.fire(loadingAlertOptions);

        const token = localStorage.getItem("access_token");

        const config = {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        };
        const response = await axios.get('/api/user/reset-token', config);
        newToken.value = response.data.token;
        localStorage.setItem('access_token', newToken.value);
        loadingAlert.close();
        showToken.value = true;
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'Se produjo un error en la operación ' + error,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
        })
    }
};

const closeModal = () => {
    showToken.value = false;
};

const copy = () => {
    const valueToCopy = newToken.value;

    const hiddenInput = document.createElement('input');
    hiddenInput.value = valueToCopy;
    document.body.appendChild(hiddenInput);

    hiddenInput.select();
    hiddenInput.setSelectionRange(0, valueToCopy.length);
    document.execCommand('copy');

    document.body.removeChild(hiddenInput);

    Swal.fire({
        title: "Token copiado",
        icon: "success",
        showConfirmButton: false,
        timer: 1500,
    });
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Token de Autenticación</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Al regenerar o crear por primera vez su token de autenticación se eliminarán todos los tokens que haya
                creado anteriormente por lo que esos tokens ya no serán válidos de usar en el API.
                Siempre que se regenere o cree el token de autenticación se mostrará solo una vez, asegurese de guardarlo en
                un lugar seguro.
            </p>
        </header>
        <DangerButton @click="updateToken">Crear/Regenerar token</DangerButton>
        <Modal :show="showToken" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Nuevo token de autenticación
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Esto solo se podrá ver una vez, asegurese de guardar el token en un sitio seguro.
                </p>

                <div class="mt-6">
                    <div class="relative">
                        <input v-model="newToken" type="text"
                            class="w-full border-gray-300 border rounded-md disabled:text-gray-400 disabled:bg-gray-100"
                            disabled />
                        <input ref="hiddenInput" v-model="newToken" type="text" class="opacity-0 absolute" />
                        <button @click="copy"
                            class="flex items-center absolute right-2 top-1/2 transform -translate-y-1/2 px-3 py-2 text-xs font-medium text-gray-600 hover:text-blue-700">
                            <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 18 20">
                                <path
                                    d="M5 9V4.13a2.96 2.96 0 0 0-1.293.749L.879 7.707A2.96 2.96 0 0 0 .13 9H5Zm11.066-9H9.829a2.98 2.98 0 0 0-2.122.879L7 1.584A.987.987 0 0 0 6.766 2h4.3A3.972 3.972 0 0 1 15 6v10h1.066A1.97 1.97 0 0 0 18 14V2a1.97 1.97 0 0 0-1.934-2Z">
                                </path>
                                <path
                                    d="M11.066 4H7v5a2 2 0 0 1-2 2H0v7a1.969 1.969 0 0 0 1.933 2h9.133A1.97 1.97 0 0 0 13 18V6a1.97 1.97 0 0 0-1.934-2Z">
                                </path>
                            </svg>
                            <span class="copy-text">Copy</span>
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeModal"> OK </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section></template>
