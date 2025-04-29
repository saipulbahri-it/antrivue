<template>
    <div class="mx-auto mt-10 max-w-xl p-4">
        <h1 class="mb-4 text-2xl font-bold">Pilih Layanan</h1>
        <form @submit.prevent="submit">
            <Dropdown
                v-model="form.service_id"
                :options="services"
                option-label="name"
                option-value="id"
                placeholder="Pilih layanan"
                class="mb-4 w-full"
            />
            <Button
                label="Ambil Nomor Antrian"
                type="submit"
                class="p-button-primary w-full"
            />
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Button } from 'primevue';
import Dropdown from 'primevue/dropdown';

defineProps({ services: Array });

const form = useForm({ service_id: null });

function submit() {
    form.post('/queue');
}
</script>
