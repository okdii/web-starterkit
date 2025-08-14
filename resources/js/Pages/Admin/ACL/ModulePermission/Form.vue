<script setup>
import { ref } from "vue";
import BaseCard from "@/Components/BaseCard.vue";
import CompServerSideDataTable from "@/Components/ServerSideDataTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import usePrimevueHelpers from "@/Helpers/Confirmation";

const page = usePage();
const { showToast } = usePrimevueHelpers();
const props = defineProps({
    module_permission: Object,
    isCreate: Boolean,
});
const datatable = ref(null);

const filterForm = useForm({
    module_id: props.module_permission.slug,
    permission_id: null,
});

const addPermission = (permission) => {
    filterForm.permission_id = permission;
    filterForm.post(route("admin.module-permission.store"), {
        onFinish: () => {
            if (page.props.flash) {
                showToast(page.props.flash);
                datatable.value?.refreshTable();
            }
        },
    });
    filterForm.reset();
};

const deletePermission = (module_permissions) => {
    useForm({}).delete(
        route("admin.module-permission.destroy", module_permissions),
        {
            onFinish: () => {
                if (page.props.flash) {
                    showToast(page.props.flash);
                    datatable.value?.refreshTable();
                }
            },
        }
    );
};
</script>

<template>
    <Head title="Module Permission" />

    <AuthenticatedLayout>
        <BaseCard
            :title="'List of Module Permission - ' + module_permission.name"
            :back_route="route('admin.module.index')"
        >
            <template #content>
                <CompServerSideDataTable
                    ref="datatable"
                    :filter_form="filterForm"
                    :route="route('admin.ajax.module-permission.dt-permission')"
                >
                    <template #columns>
                        <Column field="no" header="No." style="width: 5%" />
                        <Column field="name" header="Name" sortable />
                        <Column
                            field="action"
                            header="Action"
                            frozen
                            style="width: 5%"
                        >
                            <template #body="slotProps">
                                <Button
                                    v-if="!slotProps.data.action.delete"
                                    icon="pi pi-plus"
                                    variant="outlined"
                                    aria-label="Add"
                                    severity="primary"
                                    @click="addPermission(slotProps.data.slug)"
                                    v-tooltip.top="'Add'"
                                />
                                <Button
                                    v-else
                                    icon="pi pi-trash"
                                    variant="outlined"
                                    aria-label="Delete"
                                    severity="danger"
                                    @click="
                                        deletePermission(
                                            slotProps.data.module_permissions_id
                                        )
                                    "
                                    v-tooltip.top="'Delete'"
                                />
                            </template>
                        </Column>
                    </template>
                </CompServerSideDataTable>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
