<script setup>
import { ref } from "vue";
import BaseCard from "@/Components/BaseCard.vue";
import CompServerSideDataTable from "@/Components/Datatable/ServerSideDataTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import CompServerSideDataTableAction from "@/Components/Datatable/ServerSideDataTableAction.vue";

const datatable = ref(null);
</script>

<template>
    <Head title="Role" />

    <AuthenticatedLayout>
        <BaseCard title="List of Roles">
            <template #button>
                <div class="w-full xl:w-auto">
                    <Link class="w-full" :href="route('central.role.create')">
                        <Button label="New" icon="pi pi-plus" iconPos="right" />
                    </Link>
                </div>
            </template>
            <template #content>
                <CompServerSideDataTable
                    ref="datatable"
                    :route="route('central.ajax.role.dt-role')"
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
                                <CompServerSideDataTableAction
                                    :item="slotProps.data"
                                    :edit_route="
                                        route(
                                            'central.role.edit',
                                            slotProps.data.slug
                                        )
                                    "
                                    :delete_route="
                                        route(
                                            'central.role.destroy',
                                            slotProps.data.slug
                                        )
                                    "
                                >
                                    <Link
                                        :href="
                                            route(
                                                'central.role-module.edit',
                                                slotProps.data.slug
                                            )
                                        "
                                    >
                                        <Button
                                            icon="pi pi-list-check"
                                            variant="outlined"
                                            aria-label="Module"
                                            v-tooltip.top="'Module'"
                                        />
                                    </Link>
                                </CompServerSideDataTableAction>
                            </template>
                        </Column>
                    </template>
                </CompServerSideDataTable>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
