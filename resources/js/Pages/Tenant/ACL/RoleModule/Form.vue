<script setup>
import { ref } from "vue";
import BaseCard from "@/Components/BaseCard.vue";
import CompServerSideDataTable from "@/Components/Datatable/ServerSideDataTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import usePrimevueHelpers from "@/Helpers/Confirmation";

const page = usePage();
const { showToast } = usePrimevueHelpers();
const props = defineProps({
    role_module: Object,
    isCreate: Boolean,
});
const datatable = ref(null);

const filterForm = useForm({
    role_id: props.role_module.slug,
    module_id: null,
});

const addModule = (module) => {
    filterForm.module_id = module;
    filterForm.post(route("tenant.role-module.store"), {
        onFinish: () => {
            if (page.props.flash) {
                showToast(page.props.flash);
                datatable.value?.refreshTable();
            }
        },
    });
    filterForm.reset();
};

const deleteModule = (role_modules) => {
    useForm({}).delete(route("tenant.role-module.destroy", role_modules), {
        onFinish: () => {
            if (page.props.flash) {
                showToast(page.props.flash);
                datatable.value?.refreshTable();
            }
        },
    });
};
</script>

<template>
    <Head title="Role Module" />

    <AuthenticatedLayout>
        <BaseCard
            :title="'List of Role Module - ' + role_module.name"
            :back_route="route('tenant.role.index')"
        >
            <template #content>
                <CompServerSideDataTable
                    ref="datatable"
                    :filter_form="filterForm"
                    :route="route('tenant.ajax.role-module.dt-module')"
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
                                    @click="addModule(slotProps.data.slug)"
                                    v-tooltip.top="'Add'"
                                />
                                <Button
                                    v-else
                                    icon="pi pi-trash"
                                    variant="outlined"
                                    aria-label="Delete"
                                    severity="danger"
                                    @click="
                                        deleteModule(
                                            slotProps.data.role_modules_id
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
