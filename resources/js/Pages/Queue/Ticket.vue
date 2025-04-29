<template>
    <div class="mx-auto mt-10 max-w-md p-4 text-center">
        <h1 class="text-3xl font-bold">Nomor Antrian Anda</h1>
        <p class="my-6 text-6xl text-blue-600">{{ queue.number }}</p>
        <p>Layanan: {{ queue.service.name }}</p>
        <Button label="Cetak" @click="print" class="mt-4" />
    </div>
</template>

<script setup>
import { Button } from 'primevue';

defineProps({ queue: Object });

function print() {
    window.print();
}
</script>
