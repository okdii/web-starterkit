<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    user: Object,
    title: String,
});
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <BaseCard :title="props.title" :back_route="route('admin.user.index')">
            <template #content>
                <div class="my-3 flex flex-wrap justify-evenly gap-5">
                    <div class="flex w-full flex-wrap items-center">
                        <div class="w-full lg:w-1/6">
                            <p class="font-extrabold">Name</p>
                        </div>
                        <div class="w-full lg:w-5/6">
                            <p>
                                {{ user.name }}
                            </p>
                        </div>
                    </div>
                    <div class="flex w-full flex-wrap items-center">
                        <div class="w-full lg:w-1/6">
                            <p class="font-extrabold">Email</p>
                        </div>
                        <div class="w-full lg:w-5/6">
                            <p>
                                {{ user.email }}
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
