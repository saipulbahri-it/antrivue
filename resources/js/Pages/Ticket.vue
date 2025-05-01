<template>
    <Head title="Ambil Nomor" />
    <div
        class="mx-auto flex h-screen flex-1 flex-col items-center justify-around bg-cover bg-center bg-no-repeat text-white sm:overflow-x-hidden sm:px-4 sm:py-4 lg:overflow-hidden"
    >
        <img
            id="background"
            class="-left-20 top-0 max-w-[877px] opacity-[.48]"
            src="/images/background.svg"
        />
        <div class="flex flex-col gap-2 pt-5 text-center">
            <div class="flex flex-col items-center text-center">
                <img
                    id="logo"
                    src="/images/pn-takengon-logo-300x300.png"
                    class="w-1/3"
                />
            </div>
            <div class="text-center">
                <ApplicationLogo />
            </div>
            <div>
                <h2 class="mb-5 text-xl font-bold uppercase lg:text-3xl">
                    PENGADILAN NEGERI TAKENGON
                </h2>
                <h2 class="text-xl font-bold uppercase text-yellow-600">
                    Ambil Nomor
                </h2>
                <div
                    class="text-sm italic text-gray-400 text-yellow-600 opacity-[0.6] sm:text-lg"
                >
                    Silahkan pilih layanan yang ingin Anda ambil nomornya
                </div>
            </div>
        </div>
        <Toast group="bc" class="right-0 flex flex-col text-center">
            <template #closeicon> </template>
            <template #message>
                <div
                    style="text-align: center; font-size: 20px"
                    v-if="queue"
                    class="w-full"
                >
                    <p>=== Nomor Antrian ===</p>
                    <h1 style="font-size: 50px">{{ queue?.number }}</h1>
                    <h3>{{ queue?.service?.name }}</h3>
                    <p>=====================</p>
                    <p>{{ new Date().toLocaleString() }}</p>
                    <p>=====================</p>
                </div>
            </template>
        </Toast>
        <div
            class="my-5 grid grid-cols-1 gap-5 p-5 sm:grid-cols-1 sm:gap-8 lg:grid-cols-2 lg:gap-8"
        >
            <div
                v-for="service in services"
                :key="service.id"
                class="text-white-100"
            >
                <Button v-slot="slotProps" asChild>
                    <button
                        v-bind="slotProps.a11yAttrs"
                        @click="newTicket(service.id)"
                        :disabled="form.processing"
                        class="p-print flex-start flex w-full justify-between gap-5 rounded-full bg-gradient-to-t from-green-900 via-green-900 to-green-600 shadow-xl transition duration-300 hover:shadow-2xl active:scale-95 active:bg-green-900 active:shadow-lg"
                    >
                        <div
                            class="flex w-full items-center justify-around gap-2 px-4 py-2 sm:px-7 sm:py-5"
                        >
                            <div
                                class="mr-2 flex flex-col items-baseline text-left sm:mr-5"
                            >
                                <div class="truncate text-sm text-yellow-200">
                                    Layanan
                                </div>
                                <h2
                                    class="truncate text-sm font-bold sm:text-xl lg:text-2xl"
                                >
                                    {{ service.name }}
                                </h2>
                            </div>
                            <span
                                class="ml-auto text-2xl sm:text-3xl lg:scale-105"
                            >
                                <i
                                    class="pi pi-print"
                                    style="font-size: 2.5rem"
                                ></i>
                            </span>
                        </div>
                    </button>
                </Button>
            </div>
        </div>
        <div class="px-5 pb-10 text-base text-white">@2025</div>
        <div class="hidden">
            <div ref="componentRef">
                <div style="text-align: center; font-size: 20px">
                    <br />
                    <p>===== Nomor Antrian =====</p>
                    <h1 style="font-size: 50px">{{ queue?.number }}</h1>
                    <h3>{{ queue?.service?.name }}</h3>
                    <p>=====================</p>
                    <p>{{ new Date().toLocaleString() }}</p>
                    <p>=====================</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Service } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button, Toast } from 'primevue';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

interface Queue {
    id: number;
    number: string;
    service: Service;
}

import { PropType } from 'vue';

const { services, queue } = defineProps({
    services: Array<Service>,
    queue: Object as PropType<Queue | null>,
});

const form = useForm<{
    service_id: number | null;
}>({
    service_id: null,
});

const newTicket = (serviceId: number) => {
    // Logic to add a queue for the selected service
    form.service_id = serviceId;
    form.post('/ticket', {
        headers: {
            Accept: 'application/json',
        },
        onSuccess: () => {
            showModal.value = true;

            toast.add({
                severity: 'info',
                group: 'bc',
                life: 3000,
            });

            handlePrint();
        },
        onError: () => {
            // Handle error, e.g., show an error message
        },
    });
};

import { ref } from 'vue';

import { useVueToPrint } from 'vue-to-print';

const componentRef = ref();

const showModal = ref(false);

const { handlePrint } = useVueToPrint({
    content: componentRef,
    documentTitle: 'Tiket Nomor Antrian',
});

// const printTicket = () => {
//     const printWindow = window.open('', '', 'width=300,height=600');
//     if (printWindow) {
//         printWindow.document.write(`
//               <div style="text-align: center; font-size: 20px;">
//                 <p>=== Nomor Antrian ===</p>
//                 <h1 style="font-size: 50px;">${queue?.number}</h1>
//                 <h3>Nama Layanan</h3>
//                 <p></p>
//                 <p>${new Date().toLocaleString()}</p>
//                 <p>=====================</p>
//               </div>
//             `);
//         printWindow.document.close();
//         printWindow.focus();
//         printWindow.print();
//         printWindow.close();
//     } else {
//         console.error('Failed to open print window.');
//     }
// };
</script>
<style>
body {
    background-color: #003110;
}
#background {
    z-index: -1;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.p-print {
    transition: all 0.3s ease;
}
</style>
