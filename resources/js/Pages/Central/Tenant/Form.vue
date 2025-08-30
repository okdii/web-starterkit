<script setup>
import FormCard from "@/Components/FormCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { useForm as inertiaUseForm } from "@inertiajs/vue3";
import * as yup from "yup";
import { ref } from "vue";

const props = defineProps({
    tenant: Object,
    isCreate: Boolean,
    title: String,
});

const form_route = props.isCreate
    ? ref(route("central.tenant.store"))
    : ref(route("central.tenant.update", props.tenant?.id));
const formDetail = {
    initialValues: {
        _method: props.isCreate ? "post" : "put",
        name: props.tenant?.name,
        domain: props.tenant?.relation_domain?.domain,
        // database: props.tenant?.tenancy_db_name,
    },
    validationSchema: yup.object({
        name: yup.string().required("Name is required"),
        domain: yup.string().required("Domain is required"),
        // database: yup.string().required("Database name is required"),
    }),
};
const form = inertiaUseForm(formDetail.initialValues);
const formValidator = useVeeForm(formDetail);
const errors = formValidator.errors;
const [name] = formValidator.defineField("name");
const [domain] = formValidator.defineField("domain");
// const [database] = formValidator.defineField("database");
</script>

<template>
    <Head title="Tenant" />

    <AuthenticatedLayout>
        <FormCard
            :title="props.title"
            :form="form"
            :form_validator="formValidator"
            :back_route="route('central.tenant.index')"
            :form_route="form_route"
        >
            <template #content>
                <div class="flex flex-col gap-1">
                    <label for="name">Name</label>
                    <InputText
                        type="text"
                        name="name"
                        v-model="name"
                        :invalid="errors.name || form?.errors?.name"
                        placeholder="Name"
                        fluid
                    />
                    <Message
                        v-if="errors.name || form?.errors?.name"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{ errors.name ?? form?.errors.name }}</Message
                    >
                </div>
                <div class="flex flex-col gap-1">
                    <label for="domain">Domain</label>
                    <InputText
                        type="text"
                        name="domain"
                        v-model="domain"
                        :invalid="errors.domain || form?.errors?.domain"
                        placeholder="Domain"
                        fluid
                    />
                    <Message
                        v-if="errors.domain || form?.errors?.domain"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{ errors.domain ?? form?.errors.domain }}</Message
                    >
                </div>
                <!-- <div class="flex flex-col gap-1">
                    <label for="database">Database Name</label>
                    <InputText
                        type="text"
                        name="database"
                        v-model="database"
                        :invalid="errors.database || form?.errors?.database"
                        placeholder="Database Name"
                        fluid
                    />
                    <Message
                        v-if="errors.database || form?.errors?.database"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{ errors.database ?? form?.errors.database }}</Message
                    >
                </div> -->
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>
