<template>
    <Head title="Counters" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="p-2 text-lg font-semibold leading-tight text-gray-800">
                Counters
            </h2>
        </template>
        <div class="mx-auto max-w-7xl rounded-lg px-4 py-6 sm:px-6 lg:px-8">
            <div class="counter-list rounded-lg bg-white p-3 shadow-sm">
                <DataTable size="small" :value="counters" class="text-sm">
                    <template #header>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold">Counters</h3>
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
                                {{ counters.length }} counters
                            </p>
                        </div>
                    </template>
                    <template #empty>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">
                                No counters found.
                            </p>
                        </div>
                    </template>
                    <Column field="name" header="Name"></Column>
                    <Column field="service.name" header="Service" />
                    <Column field="ip_address" header="IP Address" />
                    <Column field="user.name" header="Petugas" />
                    <Column
                        field="queues_count"
                        header="Queues"
                        class="important text-center"
                    >
                        <template #body="{ data }">
                            <Badge
                                v-if="data.queues_count"
                                :value="data.queues_count"
                                severity="info"
                            />
                        </template>
                    </Column>
                    <Column field="queues_skipped_count" header="Skip">
                        <template #body="{ data }">
                            <Badge
                                v-if="data.queues_skipped_count"
                                :value="data.queues_skipped_count"
                                severity="info"
                            />
                        </template>
                    </Column>
                    <Column field="queues_done_count" header="Done">
                        <template #body="{ data }">
                            <Badge
                                v-if="data.queues_done_count"
                                :value="data.queues_done_count"
                                severity="info"
                            />
                        </template>
                    </Column>
                    <Column
                        field="last_queue_called.number"
                        header="Calling Number"
                    />
                    <Column header-class="text-center" :sortable="false">
                        <template #body="{ data }">
                            <div class="flex justify-end gap-2 text-end">
                                <Button
                                    rounded
                                    icon="pi pi-eye"
                                    class="p-button-sm"
                                    outlined
                                    severity="info"
                                    @click="router.get(`/counter/${data.id}`)"
                                />
                                <Button
                                    rounded
                                    icon="pi pi-pencil"
                                    class="p-button-sm"
                                    outlined
                                    severity="warn"
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
            </div>
            <!-- Create/Edit Role Modal -->
            <Dialog
                v-model:visible="openModal"
                modal
                :header="form.id ? 'Edit Counter' : 'New Counter'"
                :style="{ width: '35rem' }"
            >
                <form
                    class="flex w-full flex-col gap-4"
                    @submit.prevent="handleSubmit"
                >
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
                        <span
                            v-if="form.errors.name"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.name }}
                        </span>
                    </div>
                    <div class="flex justify-between gap-5">
                        <div class="inline-flex flex-col gap-2">
                            <IftaLabel class="w-full md:w-56">
                                <Select
                                    id="service"
                                    v-model="form.service_id"
                                    :options="services"
                                    option-value="id"
                                    option-label="name"
                                    placeholder="Select Service"
                                    :invalid="
                                        form.errors.service_id ? true : false
                                    "
                                    class="w-full"
                                    :variant="
                                        form.service_id ? 'filled' : 'outlined'
                                    "
                                />
                                <label for="service"> Service </label>
                            </IftaLabel>
                            <span
                                v-if="form.errors.service_id"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.service_id }}
                            </span>
                        </div>
                        <div class="inline-flex w-full flex-col gap-2">
                            <FloatLabel variant="in">
                                <InputText
                                    id="ip_address"
                                    v-model="form.ip_address"
                                    :invalid="
                                        form.errors.ip_address ? true : false
                                    "
                                    :variant="
                                        form.ip_address ? 'filled' : 'outlined'
                                    "
                                    fluid
                                />
                                <label for="ip">IP Address</label>
                            </FloatLabel>
                            <span
                                v-if="form.errors.ip_address"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.ip_address }}
                            </span>
                        </div>
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

            <!-- Delete Confirmation -->
            <ConfirmDialog>
                <template #message="{ message }">
                    <div class="flex flex-col items-center">
                        <p class="mt-1 text-sm">
                            {{ message.message }}
                        </p>
                    </div>
                </template>
            </ConfirmDialog>
            <Toast />
        </div>
    </AuthenticatedLayout>
</template>
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Counter, Service } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    Badge,
    Button,
    Column,
    ConfirmDialog,
    DataTable,
    Dialog,
    FloatLabel,
    IftaLabel,
    InputText,
    Select,
    Toast,
} from 'primevue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

import { ref } from 'vue';

defineProps<{
    counters: Counter[];
    services: Service[];
}>();

const toast = useToast();

// form
const openModal = ref(false);

const selected = ref<number | null>(null);

const form = useForm<{
    id: number | null;
    name: string;
    service_id: number | null;
    ip_address: string | null;
}>({
    id: null,
    name: '',
    service_id: null,
    ip_address: null,
});

const showCreateModal = () => {
    form.reset();
    openModal.value = true;
};

const showEditModal = (
    counter: Counter & {
        id: number;
        service_id: number | null;
        ip_address: string | null;
    },
) => {
    form.id = counter.id;
    form.name = counter.name;
    form.service_id = counter.service_id;
    form.ip_address = counter.ip_address;
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
        form.put(`/counter/${form.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Counter updated successfully',
                    life: 3000,
                });
                form.reset();
                openModal.value = false;
            },
            onError: (errors) => {
                console.error('Error updating Counter:', errors);
            },
        });
    } else {
        form.post('/counter', {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Counter saved successfully',
                    life: 3000,
                });
                form.reset();
                openModal.value = false;
            },
            onError: (errors) => {
                console.error('Error saving Counter:', errors);
            },
        });
    }
};

const confirmCounter = useConfirm();

const requireDeleteConfirmation = (Counter: Counter & { id: number }) => {
    confirmCounter.require({
        message: `Are you sure you want to delete this Counter ${Counter.name} ?`,
        header: 'Delete Confirmation',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Yes',
        rejectLabel: 'No',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(`/counter/${Counter.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.add({
                        severity: 'info',
                        summary: 'Success',
                        detail: 'Counter deleted successfully',
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
                detail: 'Counter deletion cancelled',
                life: 3000,
            });
        },
    });
};
</script>
