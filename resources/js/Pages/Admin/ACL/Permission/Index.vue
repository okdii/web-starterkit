<script setup>
import { ref } from "vue";
import BaseCard from "@/Components/BaseCard.vue";
import CompServerSideDataTable from "@/Components/Datatable/ServerSideDataTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import usePrimevueHelpers from "@/Helpers/Confirmation";

const page = usePage();
const { showToast, showSaveConfirm } = usePrimevueHelpers();
const datatable = ref(null);

const addPermission = () => {
    showSaveConfirm({
        header: "Are you sure to add new permission?",
        accept: () => {
            useForm({}).post(route("admin.permission.store"), {
                onFinish: () => {
                    if (page.props.flash) {
                        showToast(page.props.flash);
                    }
                },
            });
        },
    });
};
</script>

<template>
    <Head title="Permission" />

    <AuthenticatedLayout>
        <BaseCard title="List of Permission">
            <template #button>
                <div class="w-full xl:w-auto">
                    <Button
                        label="New"
                        icon="pi pi-plus"
                        iconPos="right"
                        @click="addPermission"
                    />
                </div>
            </template>
            <template #content>
                <CompServerSideDataTable
                    ref="datatable"
                    :route="route('admin.ajax.permission.dt-permission')"
                >
                    <template #columns>
                        <Column field="no" header="No." style="width: 5%" />
                        <Column field="name" header="Name" sortable />
                    </template>
                </CompServerSideDataTable>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
