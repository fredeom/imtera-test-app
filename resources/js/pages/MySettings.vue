<script setup lang="ts">
import MyControllers from '@/actions/App/Http/Controllers';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit } from '@/routes/MySettings';
import { type BreadcrumbItem } from '@/types';
import { ref, onMounted, computed } from 'vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Настройки',
        href: edit().url,
    },
];

defineProps<{ url: String }>()

const inputUrl = ref();
const csrfToken = ref();

onMounted(() => {
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    inputUrl.value.focus();
});

</script>

<template>
    <Head title="Настройки" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 p-4">
            <HeadingSmall title="Подключить Яндекс" />
            <div style="font-size: 12px;">Укажите ссылку на Яндекс, пример <u>https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/</u></div>
            <Form
                v-bind="MyControllers.MySettingsController.update.form()"
                class="space-y-6"
                v-slot="{ errors, processing, recentlySuccessful }"
            >

                <div class="grid gap-2 col-6">
                    <Input
                        ref="inputUrl"
                        id="url"
                        class="mt-1 block w-200"
                        name="url"
                        :value="url"
                        required
                        autocomplete="url"
                        placeholder="https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/"
                    />
                    <InputError class="mt-2" :message="errors.url" />
                </div>

                <div class="flex items-center gap-4">
                    <Button
                        :disabled="processing"
                        data-test="update-profile-button"
                    >
                        Сохранить
                    </Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-show="recentlySuccessful"
                            class="text-sm text-neutral-600"
                        >
                            Сохранено.
                        </p>
                    </Transition>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>

<style scoped>

button {
    background: #339AF0;
    color: white;
    padding: 3px;
    font-size: 12px;
    border-radius: 7px;
    width: 128px;
    height: 25px;
    border: 1px solid black;
}

input {
    border: 1px solid black;
}

</style>
