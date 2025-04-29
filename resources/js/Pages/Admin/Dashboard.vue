<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Counter, User } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ConfirmDialog, DatePicker } from 'primevue';
import { ref, watch } from 'vue';
import CounterCrud from './Partials/CounterCrud.vue';
import ServiceCrud from './Partials/ServiceCrud.vue';
import UserCrud from './Partials/UserCrud.vue';

interface Service {
    id: number;
    name: string;
    prefix: string | null;
    queues_count: number;
    queues_waiting_count: number;
    queues_done_count: number;
    queues_skipped_count: number;
}

const { selectedDate } = defineProps<{
    users: User[];
    counters: Counter[];
    services: Service[];
    selectedDate: Date | unknown;
}>();

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

watch(localSelectedDate, async (newValue) => {
    if (newValue) {
        try {
            await router.get(
                '/dashboard',
                { selectedDate: parseDate(newValue) },
                { preserveState: true },
            );
        } catch (error) {
            console.error('Error fetching services:', error);
        }
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl font-semibold text-gray-800 dark:text-gray-200"
                >
                    Dashboard
                </h2>
                <DatePicker
                    v-model="localSelectedDate"
                    date-format="yy-mm-dd"
                    size="small"
                    placeholder="Select Date"
                    :showIcon="true"
                />
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl gap-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4">
                    <div class="service-list rounded-lg bg-white p-3">
                        <ServiceCrud :services="services" />
                    </div>
                    <div class="counter-list rounded-lg bg-white p-3">
                        <CounterCrud
                            :counters="counters"
                            :services="services"
                        />
                    </div>
                    <div class="user-list rounded-lg bg-white p-3">
                        <UserCrud :users="users" :counters="counters" />
                    </div>
                </div>
            </div>
        </div>
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
    </AuthenticatedLayout>
</template>
