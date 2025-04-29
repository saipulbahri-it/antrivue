<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <div class="mr-auto">
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        {{ counter?.name }}
                    </h2>
                    <div>{{ counter?.service.name }}</div>
                </div>
                <div class="ml-auto">
                    <Button
                        rounded
                        outlined
                        icon="pi pi-times"
                        class="p-button-danger"
                        @click="router.get('/counter')"
                    />
                </div>
            </div>
        </template>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 xl:flex-row">
                <div
                    class="border-surface rounded-2xl bg-white px-7 py-5 xl:w-96 dark:bg-gray-800"
                >
                    <div class="mb-6 flex items-center gap-6">
                        <div class="text-color flex-1 font-semibold leading-6">
                            Sedang Dipanggil :
                        </div>
                    </div>
                    <div
                        v-if="queue"
                        class="flex flex-col justify-between gap-4"
                    >
                        <div>
                            <div
                                class="mb-5 rounded-lg border border-gray-300 p-4"
                            >
                                <h2
                                    class="my-4 text-5xl font-bold text-blue-600"
                                >
                                    {{ queue.number }}
                                </h2>
                                <div class="text-gray-500">
                                    {{ queue.called_at || 'calling...' }}
                                </div>
                            </div>
                            <div class="flex max-w-md justify-between gap-3">
                                <Button
                                    v-if="queue.status != 'done'"
                                    label="Done"
                                    class="p-button-contrast mr-2"
                                    @click="finish"
                                    :disabled="
                                        queue.status == 'done' ||
                                        queue.called_at
                                            ? false
                                            : true
                                    "
                                />
                                <Button
                                    label="Next"
                                    class="p-button-primary"
                                    @click="callNext"
                                />
                            </div>
                        </div>
                    </div>
                    <Card v-else>
                        <template #content>
                            <div
                                v-if="$page.props.flash.message"
                                class="rounded-lg border border-blue-500 bg-blue-100 px-4 py-3 text-blue-700"
                                role="alert"
                            >
                                <p class="text-sm">
                                    {{ $page.props.flash.message }}
                                </p>
                            </div>
                            <Button
                                label="Call"
                                class="p-button-primary mt-4 w-full"
                                @click="callNext"
                            />
                        </template>
                    </Card>
                </div>

                <div class="card border-surface flex-1 rounded-xl bg-white p-2">
                    <DataTable
                        :value="queues"
                        scrollable
                        scrollHeight="400px"
                        tableStyle="min-width: 30rem"
                        size="small"
                        class="w-full text-sm"
                    >
                        <template #header>
                            <div
                                class="flex flex-wrap items-center justify-between gap-2"
                            >
                                <span class="text-xl font-bold">Queues</span>
                                <SelectButton
                                    :options="statuses"
                                    v-model="statusQ"
                                    option-value="value"
                                    option-label="label"
                                    size="small"
                                    @change="handleStatus"
                                />
                            </div>
                        </template>
                        <Column
                            field="number"
                            header="Number"
                            body-class="font-bold font-mono"
                        />
                        <Column
                            field="counter.name"
                            header="Counter"
                            v-if="statusQ != 'waiting'"
                        />
                        <Column header="Status">
                            <template #body="slotProps">
                                <Badge
                                    :value="slotProps.data.status"
                                    :severity="
                                        getSeverity(slotProps.data.status)
                                    "
                                />
                            </template>
                        </Column>
                        <Column
                            field="created_at"
                            header="Created At"
                            header-class="w-40 text-sm text-right"
                        >
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
                                        second: '2-digit',
                                        hour12: false,
                                    })
                                }}
                            </template>
                        </Column>
                        <Column
                            field="called_at"
                            header="Called At"
                            header-class="w-40 text-sm text-right"
                            v-if="statusQ == 'calling'"
                        >
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
                                            second: '2-digit',
                                            hour12: false,
                                        })
                                    }}
                                </div>
                            </template>
                        </Column>
                        <template #footer>
                            In total there are
                            {{ queues ? queues.length : 0 }} queues.
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useIntervalFn } from '@vueuse/core';

import { Counter, Queue } from '@/types';
import { router } from '@inertiajs/vue3';
import { Badge, Button, Card, Column, DataTable, SelectButton } from 'primevue';
import { ref } from 'vue';

const { calledQueue, counter, status } = defineProps<{
    counter: Counter & { user: { name: string } };
    calledQueue: Queue | unknown;
    queues: Queue[];
    statuses: Array<any>;
    status: string;
}>();

interface FlashMessage {
    message?: string;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $page: {
            props: {
                flash: FlashMessage;
            };
        };
    }
}

const queue = ref<Queue | null>(
    typeof calledQueue === 'object' &&
        calledQueue !== null &&
        'id' in calledQueue &&
        'number' in calledQueue
        ? (calledQueue as Queue)
        : null,
);

const statusQ = ref(status);

const handleStatus = (event: { value: string }) => {
    const statusValue = event.value;
    if (counter?.id) {
        router.reload({
            only: ['queues'],
            data: { status: statusValue },
        });
    } else {
        console.error('Counter ID is undefined or null.');
    }
};

const callNext = () => {
    router.get(`/queue/call/${counter?.id}`, {
        preserveScroll: false,
    });
};

const finish = () => {
    router.post(
        `/queue/${queue.value?.id}/finish`,
        {},
        {
            onSuccess: () => {
                if (queue.value) {
                    queue.value.status = 'done';
                }
            },
        },
    );
};

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const skip = () => {
    router.post(
        queue.value ? `/queue/${queue.value.id}/skip` : '',
        {},
        {
            onSuccess: () => {
                callNext();
            },
        },
    );
};

const poll = async () => {
    if (queue.value) {
        console.log(queue.value.id);
        await fetchQueueById(queue.value.id);
    }
};

useIntervalFn(async () => {
    poll();
}, 3000);

async function fetchQueueById(id: number) {
    try {
        const res = await fetch(`/queue/${id}`);
        const data = await res.json();
        console.log(data);
        queue.value = data;
        if (data) {
            return data;
        } else {
            return null;
        }
    } catch (error) {
        console.error('Gagal fetch queue:', error);
        return null;
    }
}

const getSeverity = (status: string) => {
    switch (status) {
        case 'waiting':
            return 'warn';

        case 'calling':
            return 'success';

        case 'done':
            return 'contrast';

        case 'skipped':
            return 'danger';

        default:
            return undefined;
    }
};
</script>
