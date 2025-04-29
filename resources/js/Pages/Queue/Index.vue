<template>
    <Head title="Queue" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <div class="mr-auto">
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        Queue
                    </h2>
                </div>
                <div class="ml-auto">
                    <DatePicker
                        v-model="localSelectedDate"
                        date-format="yy-mm-dd"
                        size="small"
                        placeholder="Select Date"
                        :showIcon="true"
                    />
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-5 flex gap-2">
                    <div class="relative">
                        <Dropdown
                            v-model="filterForm.service_id"
                            :options="[
                                { name: 'Semua', id: '' },
                                ...serviceOptions,
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="Pilih layanan"
                        >
                        </Dropdown>
                        <label
                            for="service"
                            class="absolute left-0 top-0 -translate-y-full transform"
                        >
                            <span class="text-sm text-gray-500"> Layanan </span>
                        </label>
                    </div>

                    <div class="relative">
                        <Dropdown
                            v-model="filterForm.status"
                            :options="[
                                { label: 'Semua', value: '' },
                                ...queueStatuses,
                            ]"
                            option-value="value"
                            option-label="label"
                            placeholder="Pilih Status"
                        >
                        </Dropdown>
                        <label
                            for="service"
                            class="absolute left-0 top-0 -translate-y-full transform"
                        >
                            <span class="text-sm text-gray-500"> Status </span>
                        </label>
                    </div>

                    <div class="relative">
                        <Dropdown
                            v-model="filterForm.counter_id"
                            :options="[
                                { name: 'Semua', id: '' },
                                ...counterOptions,
                            ]"
                            option-value="id"
                            option-label="name"
                            placeholder="Pilih Counter"
                            class="w-full"
                        >
                        </Dropdown>
                        <label
                            for="service"
                            class="absolute left-0 top-0 -translate-y-full transform"
                        >
                            <span class="text-sm text-gray-500"> Counter </span>
                        </label>
                    </div>

                    <Button class="ml-auto" @click="visible = true">
                        Ambil Nomor
                    </Button>
                </div>
                <div
                    class="overflow-hidden bg-white text-sm shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <DataTable
                            :value="queues"
                            size="small"
                            responsiveLayout="scroll"
                            class="w-full"
                            :paginator="true"
                            :rows="10"
                            :rows-per-page-options="[10, 20, 50]"
                            :empty-message="queues.length === 0"
                            :show-current-page-report="true"
                        >
                            <Column field="id" header="ID"></Column>
                            <Column field="service.name" header="Layanan" />
                            <Column field="counter.name" header="Counter" />
                            <Column field="number" header="Nomor"></Column>
                            <Column field="status" header="Status">
                                <template #body="slotProps">
                                    <Tag
                                        :value="slotProps.data.status"
                                        :severity="
                                            variantStatus(slotProps.data.status)
                                        "
                                    />
                                </template>
                            </Column>
                            <Column field="created_at" header="Tanggal">
                                <template #body="slotProps">
                                    {{
                                        new Date(
                                            slotProps.data.created_at,
                                        ).toLocaleString('id-ID', {
                                            day: 'numeric',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            hour12: false,
                                        })
                                    }}
                                </template>
                            </Column>
                            <Column field="called_at" header="Dipanggil">
                                <template #body="slotProps">
                                    <div v-if="slotProps.data.called_at">
                                        {{
                                            new Date(
                                                slotProps.data.called_at,
                                            ).toLocaleString('id-ID', {
                                                day: 'numeric',
                                                month: 'short',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                hour12: false,
                                            })
                                        }}
                                    </div>
                                </template>
                            </Column>
                            <template #footer>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        Total
                                        {{ queues ? queues.length : 0 }}
                                        antrian.
                                    </span>
                                </div>
                            </template>
                            <template #empty>
                                <div class="text-center">
                                    <p class="text-sm text-gray-500">
                                        Tidak ada antrian.
                                    </p>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <Dialog
            v-model:visible="visible"
            modal
            header="Ambil Nomor Antrian"
            :style="{ width: '25rem' }"
        >
            <span class="text-surface-500 dark:text-surface-400 mb-8 block">
                Pilih Layanan.
            </span>
            <div class="mb-8 flex flex-col items-center gap-4">
                <div
                    v-for="service in services"
                    :key="service.id"
                    class="w-full"
                >
                    <Button
                        size="large"
                        severity="secondary"
                        variant="outlined"
                        class="w-full p-4"
                        @click="addQueue(service.id)"
                        :label="service.name"
                        :iconPos="'left'"
                    >
                        {{ service.name }}
                    </Button>
                </div>
            </div>
        </Dialog>
    </AuthenticatedLayout>
</template>
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Counter, Service } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    Button,
    Column,
    DataTable,
    DatePicker,
    Dialog,
    Dropdown,
    Tag,
} from 'primevue';

import { ref, watch } from 'vue';

const { services, counters, selectedDate, status, service_id, counter_id } =
    defineProps<{
        queues: Array<[]>;
        services: Service[];
        counters: Counter[];
        queueStatuses: Array<[]>;
        selectedDate: Date | unknown;
        status: any;
        service_id: any;
        counter_id: any;
    }>();

const serviceOptions = ref(
    services.map((service) => ({
        ...service,
        id: String(service.id),
    })),
);

const counterOptions = ref(
    counters.map((counter) => ({
        ...counter,
        id: String(counter.id),
    })),
);

const localSelectedDate = ref<Date | null>(
    typeof selectedDate === 'string' ? new Date(selectedDate) : null,
);

const parseDate = (date: Date) => {
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    };
    const parts = new Intl.DateTimeFormat('id', options).formatToParts(date);
    const year = parts.find((part) => part.type === 'year')?.value;
    const month = parts.find((part) => part.type === 'month')?.value;
    const day = parts.find((part) => part.type === 'day')?.value;
    return `${year}-${month}-${day}`;
};

const visible = ref(false);

const form = useForm<{ service_id: number | null }>({
    service_id: null,
});

const filterForm = useForm({
    service_id: service_id,
    counter_id: counter_id,
    status: status,
});

watch(
    filterForm,
    async (newValue) => {
        const params = {
            service_id: newValue.service_id,
            counter_id: newValue.counter_id,
            status: newValue.status,
        };

        router.reload({
            only: ['queues'],
            data: {
                ...params,
                selectedDate: localSelectedDate.value
                    ? parseDate(localSelectedDate.value)
                    : null,
            },
        });
    },
    { deep: true },
);

watch(localSelectedDate, async (newValue) => {
    if (newValue) {
        try {
            filterForm.reset();
            await router.get(
                '/queue',
                { selectedDate: parseDate(newValue) },
                { preserveState: true },
            );
        } catch (error) {
            console.error('Error fetching services:', error);
        }
    }
});

const addQueue = (serviceId: number) => {
    form.service_id = serviceId;

    form.post('/queue', {
        onSuccess: (d) => {
            console.log('Queue added successfully', d);
            visible.value = false;
        },
        onError: (errors) => {
            console.error('Failed to add queue:', errors);
            visible.value = true;
        },
    });
};

const variantStatus = (status: string) => {
    switch (status) {
        case 'waiting':
            return 'warn';

        case 'calling':
            return 'success';

        case 'done':
            return 'info';

        case 'skipped':
            return 'danger';

        default:
            return undefined;
    }
};
</script>
