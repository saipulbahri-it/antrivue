<template>
    <DataTable
        class="w-full text-sm"
        :value="users"
        size="small"
        @row-select="(e) => (form.name = e.data.name)"
    >
        <Column field="name" header="Name"></Column>
        <Column field="email" header="Email"></Column>
        <Column field="role" header="Role"></Column>
        <Column field="counter.name" header="Counter"></Column>
        <Column header-class="text-center" :sortable="false">
            <template #body="{ data }">
                <div class="flex justify-end gap-2 text-end">
                    <Button
                        rounded
                        icon="pi pi-key"
                        class="p-button-sm"
                        outlined
                        severity="primary"
                        @click="changePassword(data)"
                    />
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
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold">Users</h3>
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
    </DataTable>

    <!-- Create/Edit Role Modal -->
    <Dialog
        v-model:visible="openModal"
        modal
        :header="form.id ? 'Edit User' : 'New User'"
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
                        id="email"
                        v-model="form.email"
                        :invalid="form.errors.email ? true : false"
                        :variant="form.email ? 'filled' : 'outlined'"
                        fluid
                    />
                    <label for="email">Email</label>
                </FloatLabel>
                <span v-if="form.errors.email" class="text-sm text-red-500">
                    {{ form.errors.email }}
                </span>
            </div>

            <div v-if="!form.id" class="inline-flex flex-col gap-2">
                <FloatLabel variant="in">
                    <InputText
                        id="password"
                        v-model="form.password"
                        :invalid="form.errors.password ? true : false"
                        :variant="form.password ? 'filled' : 'outlined'"
                        type="password"
                        fluid
                    />
                    <label for="password">Password</label>
                </FloatLabel>
                <span v-if="form.errors.password" class="text-sm text-red-500">
                    {{ form.errors.password }}
                </span>
            </div>

            <div class="flex justify-between gap-2">
                <div class="inline-flex flex-col gap-2">
                    <IftaLabel class="w-full md:w-56">
                        <Select
                            id="role"
                            v-model="form.role"
                            :options="roles"
                            option-value="value"
                            option-label="label"
                            placeholder="Select Role"
                            :invalid="form.errors.role ? true : false"
                            class="w-full"
                            :variant="form.role ? 'filled' : 'outlined'"
                        />
                        <label for="role"> Role </label>
                    </IftaLabel>
                    <span v-if="form.errors.role" class="text-sm text-red-500">
                        {{ form.errors.role }}
                    </span>
                </div>
                <div class="inline-flex flex-col gap-2">
                    <IftaLabel class="w-full md:w-56">
                        <Select
                            id="counter"
                            v-model="form.counter_id"
                            :options="[
                                { name: 'Select Counter', id: null },
                                ...counters,
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="Select Counter"
                            :invalid="form.errors.counter_id ? true : false"
                            class="w-full"
                            :variant="form.counter_id ? 'filled' : 'outlined'"
                        />
                        <label for="counter"> Counter </label>
                    </IftaLabel>
                    <span
                        v-if="form.errors.counter_id"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.counter_id }}
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
    <!-- make dialog change password form with username info -->
    <Dialog
        v-model:visible="openModalPassword"
        modal
        :header="`Change Password for ${formPassword.name}`"
        :style="{ width: '35rem' }"
    >
        <form
            class="flex w-full flex-col gap-4"
            @submit.prevent="handleChangePassword"
        >
            <!-- add user Info -->
            <div class="inline-flex flex-col gap-2">
                <FloatLabel variant="in">
                    <InputText
                        id="name"
                        v-model="formPassword.name"
                        :invalid="formPassword.errors.name ? true : false"
                        :variant="formPassword.name ? 'filled' : 'outlined'"
                        fluid
                        disabled
                    />
                    <label for="name">Name</label>
                </FloatLabel>
            </div>
            <div class="inline-flex flex-col gap-2">
                <FloatLabel variant="in">
                    <InputText
                        id="new_password"
                        v-model="formPassword.new_password"
                        :invalid="
                            formPassword.errors.new_password ? true : false
                        "
                        :variant="
                            formPassword.new_password ? 'filled' : 'outlined'
                        "
                        type="password"
                        fluid
                    />
                    <label for="new_password">New Password</label>
                </FloatLabel>
                <span
                    v-if="formPassword.errors.new_password"
                    class="text-sm text-red-500"
                >
                    {{ formPassword.errors.new_password }}
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
                    label="Change Password"
                    :disabled="formPassword.processing"
                />
            </div>
        </form>
    </Dialog>

    <Toast />
</template>
<script setup lang="ts">
import { Counter, User } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import {
    Button,
    Column,
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
    users: User[];
    counters: Counter[];
}>();

const roles = ref([
    { label: 'Admin', value: 'admin' },
    { label: 'Counter', value: 'counter' },
]);

const toast = useToast();

// form
const openModal = ref(false);
const selected = ref<number | null>(null);
const form = useForm<{
    id: number | null;
    name: string;
    email: string;
    role: string;
    counter_id: number | null;
    password: string | null;
}>({
    id: null,
    name: '',
    email: '',
    role: '',
    counter_id: null,
    password: '',
});

const showCreateModal = () => {
    form.reset();
    openModal.value = true;
};

const showEditModal = (user: User & { id: number; counter_id: number }) => {
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.role = user.role;
    form.counter_id = user.counter_id;
    openModal.value = true;
};

const handleCancel = () => {
    if (form.id) {
        form.reset();
        openModal.value = false;
    }
    if (formPassword.id) {
        formPassword.reset();
        openModalPassword.value = false;
    }
};

const handleSubmit = () => {
    if (form.id) {
        form.put(`/users/${form.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'User updated successfully',
                    life: 3000,
                });
                form.reset();
                openModal.value = false;
            },
            onError: (errors) => {
                console.error('Error updating user:', errors);
            },
        });
    } else {
        form.post('/users', {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'User saved successfully',
                    life: 3000,
                });
                form.reset();
                openModal.value = false;
            },
            onError: (errors) => {
                console.error('Error saving user:', errors);
            },
        });
    }
};

// add changePassword function
const formPassword = useForm<{
    id: number | null;
    name: string;
    new_password: string;
}>({
    id: null,
    name: '',
    new_password: '',
});
// Change Password
const openModalPassword = ref(false);
const changePassword = (user: User & { id: number }) => {
    formPassword.reset();
    formPassword.id = user.id;
    formPassword.name = user.name;
    formPassword.new_password = '';
    openModalPassword.value = true;
};

const handleChangePassword = () => {
    formPassword.put(`/users/${formPassword.id}/change-password`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Password changed successfully',
                life: 3000,
            });
            formPassword.reset();
            openModalPassword.value = false;
        },
        onError: (errors) => {
            console.error('Error changing password:', errors);
        },
    });
};

const confirm = useConfirm();

const requireDeleteConfirmation = (user: User & { id: number }) => {
    confirm.require({
        message: `Are you sure you want to delete this user ${user.name} ?`,
        header: 'Delete Confirmation',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Yes',
        rejectLabel: 'No',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(`/users/${user.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.add({
                        severity: 'info',
                        summary: 'Success',
                        detail: 'User deleted successfully',
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
                detail: 'User deletion cancelled',
                life: 3000,
            });
        },
    });
};
</script>
