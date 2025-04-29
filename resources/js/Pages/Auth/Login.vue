<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { IconField, IftaLabel, InputIcon, InputText, Password } from 'primevue';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();


const routeUrl = (name: string) => {
    return route(name);
};

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="py-5">
            <div>
                <IftaLabel>
                    <IconField>
                        <InputIcon class="pi pi-user" />
                        <InputText
                            id="email"
                            v-model="form.email"
                            class="mt-1 block w-full"
                            fluid
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </IconField>
                    <label for="email">Email</label>
                </IftaLabel>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-8">
                <IftaLabel>
                    <IconField>
                        <InputIcon class="pi pi-key" />
                        <Password
                            v-model="form.password"
                            inputId="password"
                            id="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="current-password"
                            toggleMask
                            fluid
                        />
                    </IconField>

                    <label for="password">Password</label>
                </IftaLabel>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-8 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                        Remember me
                    </span>
                </label>
            </div>

            <div class="mt-10 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="routeUrl('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Lupa password?
                </Link>

                <Button
                    type="submit"
                    label="Log in"
                    severity="contrast"
                    rounded
                    class="mx-2 ms-4 block px-5"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    :loading="form.processing"
                    icon="pi pi-arrow-right"
                    size="large"
                >
                    <i class="pi pi-arrow-right px-2"></i>
                    <span class="px-4">Log in</span>
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
