<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl font-semibold text-gray-800 dark:text-gray-200"
                >
                    Counters
                </h2>
            </div>
        </template>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-center gap-2 p-4">
                    <Card
                        class="flex rounded-lg border p-4 lg:w-1/3"
                        v-for="(counter, index) in counters"
                        :key="index"
                    >
                        <template #header>
                            <div class="flex">
                                <div>
                                    <h1 class="text-2xl font-bold">
                                        {{ counter.name }}
                                    </h1>
                                    <div class="font-bold">
                                        {{ counter.service?.name }}
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <Link :href="`/counter/${counter.id}`">
                                        Show
                                    </Link>
                                </div>
                            </div>
                        </template>
                        <template #content>
                            {{ counter.service?.queues.length }} antrian
                        </template>
                        <template #footer>
                            <Button
                                label="Panggil Berikutnya"
                                class="p-button-success w-full"
                                @click="callNext"
                                :disabled="speech.isPlaying.value"
                            />
                        </template>
                    </Card>
                </div>
            </div>
        </div>

        <div class="mx-auto mt-10 max-w-xl p-4">
            <h2 class="mb-4 text-xl font-bold">Panel Loket</h2>

            <Dropdown
                v-model="form.service_id"
                :options="services"
                option-value="id"
                option-label="name"
                placeholder="Pilih layanan"
                class="mb-4 w-full"
            />

            <Dropdown
                v-model="form.counter_id"
                :options="counters"
                option-value="id"
                option-label="name"
                placeholder="Pilih loket"
                class="mb-4 w-full"
            />

            <Button
                :disabled="
                    form.processing ||
                    form.service_id === null ||
                    form.counter_id === null
                "
                :loading="form.processing"
                label="Panggil Nomor Berikutnya"
                @click="callNext"
                class="p-button-success w-full"
            />
        </div>
        <div
            bg="$vp-c-bg"
            border="$vp-c-divider 1"
            inline-flex
            items-center
            relative
            rounded
        >
            <i
                i-carbon-language
                absolute
                left-2
                opacity-80
                pointer-events-none
            />
            <select
                v-model="voice"
                px-8
                border-0
                bg-transparent
                h-9
                rounded
                appearance-none
            >
                <option bg="$vp-c-bg" disabled>Select Language</option>
                <option
                    v-for="(voice, i) in voices"
                    :key="i"
                    bg="$vp-c-bg"
                    :value="voice"
                >
                    {{ i }}
                    {{ `${voice.name} (${voice.lang})` }}
                </option>
            </select>
            <i
                i-carbon-chevron-down
                absolute
                right-2
                opacity-80
                pointer-events-none
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { Button, Card } from 'primevue';
import Dropdown from 'primevue/dropdown';

interface Counter {
    id: number;
    name: string;
    service_id: number;
    service?: {
        id: number;
        name: string;
        queues: {
            id: number;
            number: string;
            status: string;
            counter_id: number;
        }[];
    };
}

interface Service {
    id: number;
    name: string;
}

defineProps({
    services: {
        type: Array as () => Service[],
        required: true,
        default: () => [],
    },
    counters: {
        type: Array as () => Counter[],
        required: true,
        default: () => [],
    },
});

const form = useForm<{
    service_id: number | null;
    counter_id: number | null;
}>({
    service_id: null,
    counter_id: null,
});

const callNext = () => {
    const serviceId = form.service_id;
    const counterId = form.counter_id;

    if (counterId && serviceId) {
        form.service_id = serviceId;
        form.counter_id = counterId;
    }

    // form.post('/counter/call', {
    //     preserveScroll: true,
    // });

    text.value = `Antrian P-003 Loket Satu`;

    form.reset();
    play();
};

import { useSpeechSynthesis } from '@vueuse/core';
import { ref as deepRef, onMounted, shallowRef } from 'vue';

const voice = deepRef<SpeechSynthesisVoice>(
    undefined as unknown as SpeechSynthesisVoice,
);
const text = shallowRef('Nomor Antrian P-001 Loket 1');
const pitch = shallowRef(1);
const rate = shallowRef(1);

const speech = useSpeechSynthesis(text, {
    lang: 'id-ID',
    voice,
    pitch,
    rate,
});

let synth: SpeechSynthesis;

const voices = shallowRef<SpeechSynthesisVoice[]>([]);

onMounted(() => {
    if (speech.isSupported.value) {
        // load at last
        setTimeout(() => {
            synth = window.speechSynthesis;
            voices.value = synth.getVoices();
            voice.value = voices.value[198];
        });
    }
});

function play() {
    console.log(text.value);
    if (speech.status.value === 'pause') {
        console.log('resume');
        window.speechSynthesis.resume();
    } else {
        speech.speak();
    }
}
</script>
