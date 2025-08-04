<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <BaseCard title="Dashboard">
            <template #content>
                <h3>Welcome to Dashboard</h3>
                <br />
                <Link
                    :href="route('admin.user.index')"
                    class="p-button p-component"
                >
                    List of Users
                </Link>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
