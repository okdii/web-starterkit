<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    tenant: Object,
    title: String,
});
</script>

<template>
    <Head title="Tenant" />

    <AuthenticatedLayout>
        <BaseCard
            :title="props.title"
            :back_route="route('central.tenant.index')"
        >
            <template #content>
                <div class="my-3 flex flex-wrap justify-evenly gap-5">
                    <div class="flex w-full flex-wrap items-center">
                        <div class="w-full lg:w-1/6">
                            <p class="font-extrabold">Name</p>
                        </div>
                        <div class="w-full lg:w-5/6">
                            <p>
                                {{ tenant.name }}
                            </p>
                        </div>
                    </div>
                    <div class="flex w-full flex-wrap items-center">
                        <div class="w-full lg:w-1/6">
                            <p class="font-extrabold">Domain</p>
                        </div>
                        <div class="w-full lg:w-5/6">
                            <p>
                                {{ tenant?.relation_domain.domain }}
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
