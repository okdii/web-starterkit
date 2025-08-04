<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import CompServerSideDataTable from "@/Components/ServerSideDataTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <BaseCard title="List of Users">
            <template #content>
                <CompServerSideDataTable
                    :route="route('admin.ajax.user.dt-user')"
                >
                    <template #columns>
                        <Column field="name" header="Name" sortable />
                        <Column field="email" header="Email" />
                    </template>
                </CompServerSideDataTable>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
