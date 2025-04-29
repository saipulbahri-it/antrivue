<template>
    <div
        class="px-2-py-2 flex flex-1 content-center items-center justify-center bg-gray-100 lg:h-screen"
    >
        <div
            v-if="!disabledMonitor"
            class="relative grid h-full w-full gap-6 p-5 lg:grid-cols-3 lg:gap-8"
        >
            <div
                class="flex flex-1 flex-col items-start gap-6 overflow-hidden rounded-lg bg-white shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-gray-300 transition duration-300 hover:text-black/70 hover:ring-black/20 focus-visible:ring-[#FF2D20] md:row-span-3 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
            >
                <div
                    class="flex w-full items-center justify-between p-3 lg:p-6 lg:pb-6"
                >
                    <ApplicationLogo />
                    <Button
                        class="ml-auto"
                        icon="pi pi-volume-up"
                        :severity="isCalling ? 'success' : 'secondary'"
                        raised
                        rounded
                        aria-label="volume"
                    />
                </div>
                <div
                    class="queue-display relative flex w-full flex-1 flex-col justify-evenly gap-5 bg-gray-100 py-5 text-center"
                    :class="[
                        {
                            'animate-pulse': isCalling,
                            'bg-green-100': isCalling,
                            'border-5': isCalling,
                            'border-green-500': isCalling,
                        },
                    ]"
                >
                    <!-- header -->
                    <div class="text-5xl font-bold uppercase text-yellow-800">
                        Nomor Antrian
                    </div>
                    <!-- body -->
                    <div
                        class="text-nowrap font-mono text-8xl font-bold text-green-800 transition"
                    >
                        {{ currentCall?.number || '-' }}
                    </div>

                    <div
                        class="text-5xl font-bold uppercase text-yellow-800"
                        v-if="currentCall?.counter?.name"
                    >
                        <div>Menuju {{ currentCall?.counter?.name }}</div>
                        <div class="py-6-hidden text-2xl">
                            {{ currentCall?.service.name || '' }}
                        </div>
                    </div>
                </div>

                <!-- time -->
                <div
                    class="relative flex h-40 w-full flex-col items-center justify-center gap-6"
                >
                    <div id="jam" class="text-5xl font-bold">
                        {{ currentTime }}
                    </div>
                    <div id="tanggal" class="text-3xl font-bold">
                        {{ currentDate }}
                    </div>
                </div>
            </div>

            <!-- All Counter-->
            <div
                v-for="recentCall in recentCalls"
                :key="recentCall.counter_id"
                class="queue-display flex flex-1 flex-col justify-around rounded-xl bg-white p-6 shadow-lg"
                :class="[{ highlight: recentCall.highlight }]"
            >
                <div class="flex items-start items-center">
                    <h2 class="text-4xl font-bold">
                        {{ recentCall.counter_name || '-' }}
                    </h2>
                </div>
                <div
                    class="font-mono text-8xl font-bold text-gray-800 transition"
                >
                    {{ recentCall.queue_number || '-' }}
                </div>
                <div class="items-end">{{ recentCall.service_name }}</div>
            </div>
        </div>
    </div>
    <Tag class="fixed bottom-1 right-1 flex gap-2 text-sm">
        <i class="pi pi-volume-up" />
        <i> {{ selectedVoice?.name }} - {{ selectedVoice?.lang }} </i>
    </Tag>
    <Toast position="bottom-left" />
    <Dialog
        header="Tampilkan Monitor"
        v-model:visible="disabledMonitor"
        :closable="false"
        :draggable="false"
        :modal="true"
    >
        <Button
            label="Submit"
            class="p-button-success w-full"
            @click="disabledMonitor = false"
        />
    </Dialog>
</template>

<script setup lang="ts">
import { Queue } from '@/types';
import { useIntervalFn, useSpeechSynthesis } from '@vueuse/core';
import { Dialog, Tag, Toast } from 'primevue';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, shallowRef, watch } from 'vue';
import ApplicationLogo from '../../Components/ApplicationLogo.vue';

const toast = useToast();

interface Service {
    id: number;
    name: string;
}

interface Counter {
    id: number;
    name: string;
    service: Service;
    last_queue_called: Queue;
}

interface QueueCalled {
    id: number;
    number: string;
    service: Service;
    counter: Counter;
    status?: string; // Added the 'status' property as optional
}

interface RecentCalled {
    counter_id: number;
    counter_name: string;
    service_name: string;
    queue_id: number | null;
    queue_number: string | null;
    highlight?: boolean;
}

const { recentCalled } = defineProps<{
    services: Service[];
    recentCalled: RecentCalled[];
}>();

const disabledMonitor = ref(true);

const currentCall = ref<QueueCalled | null>(null);
const recentCalls = ref<RecentCalled[]>(recentCalled);

const currentTime = ref<string>('');
const currentDate = ref<string>('');

onMounted(() => {
    // Update current time and date every second
    setInterval(() => {
        const now = new Date();
        // eslint-disable-next-line @typescript-eslint/no-unused-vars
        const options: Intl.DateTimeFormatOptions = {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        };
        currentTime.value = now.toLocaleTimeString('en', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        }); // this line outputs 11:00
        currentDate.value = now.toLocaleDateString('id-ID');
    }, 1000);
});

async function fetchNextQueue(): Promise<QueueB | null> {
    try {
        const res = await fetch('/api/queue/next-call'); // Ganti endpoint sesuai API kamu
        const data = await res.json();

        if (data && data.number && data.counter) {
            return {
                number: data.number,
                counterName: data.counter.name,
                queueData: data,
            };
        } else {
            return null;
        }
    } catch (error) {
        console.error('Gagal fetch queue:', error);
        return null;
    }
}

const loadRecentCalls = async () => {
    const res = await fetch('/api/queue/recent-call');
    const data = await res.json();
    recentCalls.value = data;
};

// const previousData = ref<Record<string, string | null>>({});
const previousData = ref<Record<string, string | null>>({});

// watch recentCalls
watch(recentCalls, (newVal: RecentCalled[]) => {
    const freshCounters = newVal;

    freshCounters.forEach((counter: RecentCalled) => {
        const prevQueue = previousData.value[counter.counter_name || ''];

        if (counter.queue_number && counter.queue_number !== prevQueue) {
            counter.highlight = true;

            // Reset highlight after 5s
            setTimeout(() => {
                counter.highlight = false;
            }, 5000);
        } else {
            counter.highlight = false;
        }

        previousData.value[counter.counter_name] = counter.queue_number;
    });

    recentCalls.value = freshCounters;
});

/**
 * Voice
 */
interface QueueB {
    number: string;
    counterName: string;
    queueData: QueueCalled;
}

const queueBuffer = ref<QueueB[]>([]);
const currentQueue = ref<QueueB | null>(null);
const isCalling = ref(false);

const selectedVoice = ref<SpeechSynthesisVoice | null>(null);

// Polling ambil antrian baru setiap 5 detik
useIntervalFn(async () => {
    if (!disabledMonitor.value) {
        const newQueue = await fetchNextQueue();
        if (newQueue) {
            queueBuffer.value.push(newQueue);
        }
        await loadRecentCalls();
    }
}, 5000);

// Periksa buffer setiap 2 detik, kalau ada antrian dan tidak sedang manggil
useIntervalFn(async () => {
    if (!isCalling.value && queueBuffer.value.length > 0) {
        const next = queueBuffer.value.shift();
        if (next) {
            currentCall.value = next.queueData;
            await startCall(next.number, next.counterName, next.queueData);
        }
    }
}, 2000);

async function startCall(number: string, counterName: string, queueData: any) {
    isCalling.value = true;
    currentQueue.value = { number, counterName, queueData };

    try {
        const bell = new Audio('sound/bell.mp3');
        await bell.play();
        await new Promise((resolve) => setTimeout(resolve, 500));

        await playVoice(number, counterName);

        // toast.add({
        //     summary: currentQueue.value.number,
        //     life: 3000,
        // });

        setTimeout(() => {
            currentQueue.value = null;
            isCalling.value = false;
        }, 5000);
    } catch (error) {
        console.error('Gagal memanggil antrian:', error);
        toast.add({
            severity: 'error',
            summary: 'Gagal memanggil antrian',
            detail: error,
            life: 3000,
        });
        isCalling.value = false;
    }
}

// Fungsi playVoice async

const availableVoices = shallowRef<SpeechSynthesisVoice[]>([]);

const voice = ref<SpeechSynthesisVoice>(
    undefined as unknown as SpeechSynthesisVoice,
);

let synth: SpeechSynthesis;

onMounted(() => {
    setTimeout(() => {
        synth = window.speechSynthesis;
        const loadVoices = () => {
            const voices = synth.getVoices();
            if (voices.length > 0) {
                availableVoices.value = voices;
                selectedVoice.value =
                    voices.find(
                        (voice) =>
                            voice.lang === 'id-ID' &&
                            voice.name.includes('Google'),
                    ) || null;

                voice.value =
                    selectedVoice.value ||
                    (undefined as unknown as SpeechSynthesisVoice);
            } else {
                setTimeout(loadVoices, 500); // retry
            }
        };

        loadVoices();
        speechSynthesis.onvoiceschanged = loadVoices;
    });
});

// voice
const textToSpeak = ref<string>('');

const { speak } = useSpeechSynthesis(textToSpeak, {
    voice,
    lang: 'id-ID',
    rate: 0.95,
});

function playVoice(number: string, counterName: string): Promise<void> {
    return new Promise((resolve, reject) => {
        try {
            textToSpeak.value = `Nomor antrian ${number}, silakan menuju ${counterName}`;

            speak(); // Mulai bicara

            resolve();
        } catch (err) {
            reject(err);
        }
    });
}

// eslint-disable-next-line @typescript-eslint/no-unused-vars
function playVoicex(number: string, counterName: string): Promise<void> {
    return new Promise((resolve, reject) => {
        try {
            textToSpeak.value = `Nomor antrian ${number}, silakan menuju ${counterName}`;
            const utterance = new SpeechSynthesisUtterance(textToSpeak.value);
            utterance.lang = 'id-ID';
            utterance.rate = 0.95;

            if (selectedVoice.value) {
                utterance.voice = selectedVoice.value;
            } else {
                console.warn('Selected voice is null, using default voice');
            }

            utterance.onend = () => resolve();

            speechSynthesis.cancel();

            setTimeout(() => {
                console.log('Starting speech');
                speechSynthesis.speak(utterance);
            }, 100);
        } catch (err) {
            reject(err);
        }
    });
}
</script>
<style>
.queue-display {
    animation: fadeIn 0.5s ease;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.highlight {
    background-color: #ffeaa7;
    border-color: #e17055;
    transform: scale(1.05);
}
</style>
