<template>
    <DataTable size="small" :value="services" class="text-sm" show-gridlines>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold">Services</h3>
                </div>
                <Button
                    rounded
                    outlined
                    class="p-button-sm p-button-success"
                    icon="pi pi-plus"
                    @click="showCreateModal"
                />
            </div>
        </template>
        <template #footer>
            <div class="flex justify-between">
                <p class="text-sm text-gray-500">
                    {{ services.length }} services
                </p>
            </div>
        </template>
        <template #empty>
            <div class="text-center">
                <p class="text-sm text-gray-500">No services found.</p>
            </div>
        </template>
        <Column field="name" header="Name">
            <template #body="{ data }">
                <div class="flex gap-3">
                    <span>{{ data.name }}</span>
                    <span class="font-bold">{{ data.prefix }} </span>
                </div>
            </template>
        </Column>
        <Column
            field="queues_count"
            header="Queue"
            class="!justify-center !text-center"
            header-class="!justify-center !block"
            body-class="!text-center"
        ></Column>
        <Column
            field="queues_waiting_count"
            header="Waiting"
            class="!text-center"
            header-class="!justify-center"
            body-class="!text-center"
        ></Column>
        <Column
            field="queues_calling_count"
            header="Calling"
            class="!text-center"
            header-class="!justify-center"
            body-class="!text-center"
        ></Column>
        <Column
            field="queues_called_count"
            header="Called"
            class="!text-center"
            header-class="!justify-center"
            body-class="!text-center"
        ></Column>
        <Column
            field="queues_skipped_count"
            header="Skipped"
            class="!text-center"
            header-class="!justify-center"
            body-class="!text-center"
        ></Column>
        <Column
            field="queues_done_count"
            header="Done"
            class="!text-center"
            header-class="!justify-center"
            body-class="!text-center"
        ></Column>
        <Column header-class="text-center" :sortable="false">
            <template #body="{ data }">
                <div class="flex justify-end gap-2 text-end">
                    <Button
                        rounded
                        icon="pi pi-pencil"
                        class="p-button-sm"
                        outlined
                        severity="info"
                        @click="showEditModal(data)"
                    />
                    <Button
                        rounded
                        icon="pi pi-trash"
                        class="p-button-sm"
                        outlined
                        severity="warn"
                        @click="requireDeleteConfirmation(data)"
                    />
                </div>
            </template>
        </Column>
    </DataTable>

    <!-- Create/Edit Role Modal -->
    <Dialog
        v-model:visible="openModal"
        modal
        :header="form.id ? 'Edit service' : 'New service'"
        :style="{ width: '35rem' }"
    >
        <form class="flex w-full flex-col gap-4" @submit.prevent="handleSubmit">
            <div class="inline-flex flex-col gap-2">
                <FloatLabel variant="in">
                    <InputText
                        id="name"
                        v-model="form.name"
                        :invalid="form.errors.name ? true : false"
                        :variant="form.name ? 'filled' : 'outlined'"
                        fluid
                    />
                    <label for="name">Name</label>
                </FloatLabel>
                <span v-if="form.errors.name" class="text-sm text-red-500">
                    {{ form.errors.name }}
                </span>
            </div>

            <div class="inline-flex flex-col gap-2">
                <FloatLabel variant="in">
                    <InputText
                        id="prefix"
                        v-model="form.prefix"
                        :invalid="form.errors.prefix ? true : false"
                        :variant="form.prefix ? 'filled' : 'outlined'"
                    />
                    <label for="name">Prefix</label>
                </FloatLabel>
                <span v-if="form.errors.prefix" class="text-sm text-red-500">
                    {{ form.errors.prefix }}
                </span>
            </div>

            <div class="flex justify-between gap-2">
                <Button
                    type="button"
                    severity="secondary"
                    label="Cancel"
                    @click="handleCancel"
                />
                <Button
                    type="submit"
                    label="Simpan"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </Dialog>

    <Toast />
</template>
<script setup lang="ts">
import { Service } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import {
    Button,
    Column,
    DataTable,
    Dialog,
    FloatLabel,
    InputText,
    Toast,
} from 'primevue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

import { ref } from 'vue';

defineProps<{
    services: Service[];
}>();

const toast = useToast();

// form
const openModal = ref(false);

const selected = ref<number | null>(null);

const form = useForm<{
    id: number | null;
    name: string;
    prefix: string;
}>({
    id: null,
    name: '',
    prefix: '',
});

const showCreateModal = () => {
    form.reset();
    openModal.value = true;
};

const showEditModal = (service: Service & { id: number }) => {
    form.id = service.id;
    form.name = service.name;
    form.prefix = service.prefix ?? '';
    openModal.value = true;
};

const handleCancel = () => {
    if (form.id) {
        form.reset();
        openModal.value = false;
    }
};

const handleSubmit = () => {
    if (form.id) {
        form.put(`/service/${form.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'service updated successfully',
                    life: 3000,
                });
                form.reset();
                openModal.value = false;
            },
            onError: (errors) => {
                console.error('Error updating service:', errors);
            },
        });
    } else {
        form.post('/service', {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'service saved successfully',
                    life: 3000,
                });
                form.reset();
                openModal.value = false;
            },
            onError: (errors) => {
                console.error('Error saving service:', errors);
            },
        });
    }
};

const confirmService = useConfirm();

const requireDeleteConfirmation = (service: Service & { id: number }) => {
    confirmService.require({
        message: `Are you sure you want to delete this service ${service.name} ?`,
        header: 'Delete Confirmation',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Yes',
        rejectLabel: 'No',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(`/service/${service.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.add({
                        severity: 'info',
                        summary: 'Success',
                        detail: 'service deleted successfully',
                        life: 3000,
                    });
                },
                onError: (errors) => {
                    toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: errors[0],
                        life: 3000,
                    });
                },
            });
        },
        reject: () => {
            selected.value = null;
            toast.add({
                severity: 'warn',
                summary: 'Cancelled',
                detail: 'service deletion cancelled',
                life: 3000,
            });
        },
    });
};
</script>
