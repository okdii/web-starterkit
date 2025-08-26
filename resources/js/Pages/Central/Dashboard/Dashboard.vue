<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <BaseCard title="Dashboard">
            <template #content>
                <h3>Welcome to Dashboard</h3>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
