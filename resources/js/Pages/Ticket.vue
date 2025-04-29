<template>
    <Head title="Ambil Nomor" />
    <div
        class="mx-auto my-5 flex h-screen flex-1 flex-col items-center justify-around"
    >
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[877px]"
            src="/images/background.svg"
        />
        <div class="flex flex-col gap-2 text-center">
            <div class="text-center">
                <ApplicationLogo />
            </div>
            <h1 class="text-4xl font-bold uppercase">Ambil Nomor</h1>
            <p class="mt-1 text-lg text-gray-600">
                Silahkan pilih layanan yang ingin Anda ambil nomornya
            </p>
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
        <div class="m-5 grid grid-cols-1 gap-8 sm:grid-cols-1 lg:grid-cols-2">
            <div v-for="service in services" :key="service.id" class="w-full">
                <Button
                    label="Ambil Nomor"
                    class="p-button-secondary rounded-5 flex-start flex w-full shadow-lg"
                    icon="pi pi-print"
                    @click="newTicket(service.id)"
                    :disabled="form.processing"
                >
                    <div class="flex w-full justify-between gap-5 p-3">
                        <div
                            class="mr-10 flex flex-col items-baseline text-left"
                        >
                            <div class="text-black-500">Layanan</div>
                            <h2
                                class="text-xl font-bold sm:text-2xl lg:text-xl"
                            >
                                {{ service.name }}
                            </h2>
                        </div>
                        <i class="pi pi-print" style="font-size: 3rem"></i>
                    </div>
                </Button>
            </div>
        </div>
        <div>Kiosk</div>
        <!-- <button @click="handlePrint">Print</button> -->
        <div class="hidden">
            <div ref="componentRef">
                <div style="text-align: center; font-size: 20px">
                    <p>=== Nomor Antrian ===</p>
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
