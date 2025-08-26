<script setup>
import { ref } from "vue";
import BaseCard from "@/Components/BaseCard.vue";
import CompServerSideDataTable from "@/Components/Datatable/ServerSideDataTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import CompServerSideDataTableAction from "@/Components/Datatable/ServerSideDataTableAction.vue";

const props = defineProps({
    user_status: Object,
});

const datatable = ref(null);
const filterForm = useForm({
    status: null,
});
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <BaseCard title="List of Users">
            <template #button>
                <div class="w-full xl:w-auto">
                    <Link class="w-full" :href="route('central.user.create')">
                        <Button label="New" icon="pi pi-plus" iconPos="right" />
                    </Link>
                </div>
            </template>
            <template #content>
                <CompServerSideDataTable
                    ref="datatable"
                    :route="route('central.ajax.user.dt-user')"
                    :filter_form="filterForm"
                    :hasFilter="true"
                >
                    <template #filter>
                        <div class="">
                            <p class="font-bold mb-2">Status</p>
                            <Select
                                v-model="filterForm.status"
                                :options="user_status"
                                showClear
                                optionLabel="name"
                                placeholder="Please select"
                                class="w-full md:w-56"
                            />
                        </div>
                    </template>
                    <template #columns>
                        <Column field="no" header="No." style="width: 5%" />
                        <Column
                            field="name"
                            header="Name"
                            sortable
                            style="width: 40%"
                        />
                        <Column
                            field="email"
                            header="Email"
                            style="width: 30%"
                        />
                        <Column
                            field="status"
                            header="Status"
                            style="width: 10%"
                        >
                            <template #body="slotProps">
                                <Tag
                                    :value="slotProps.data.status.description"
                                    :severity="slotProps.data.status.severity"
                                    rounded
                                />
                            </template>
                        </Column>
                        <Column
                            field="created_at"
                            header="Register At"
                            style="width: 10%"
                        />
                        <Column
                            field="action"
                            header="Action"
                            frozen
                            style="width: 5%"
                        >
                            <template #body="slotProps">
                                <CompServerSideDataTableAction
                                    :item="slotProps.data"
                                    :show_route="
                                        route(
                                            'central.user.show',
                                            slotProps.data.slug
                                        )
                                    "
                                    :edit_route="
                                        route(
                                            'central.user.edit',
                                            slotProps.data.slug
                                        )
                                    "
                                    :delete_route="
                                        route(
                                            'central.user.destroy',
                                            slotProps.data.slug
                                        )
                                    "
                                />
                            </template>
                        </Column>
                    </template>
                </CompServerSideDataTable>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
