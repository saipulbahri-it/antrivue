<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
        <div>
            <Link href="/">
                <ApplicationLogo
                    class="w-max-80 h-20 fill-current text-gray-500"
                />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-10 py-10 shadow-xl sm:max-w-md sm:rounded-3xl dark:bg-gray-800"
        >
            <slot />
        </div>
    </div>
</template>
