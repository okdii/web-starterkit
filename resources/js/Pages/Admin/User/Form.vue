<script setup>
import FormCard from "@/Components/FormCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { useForm as inertiaUseForm } from "@inertiajs/vue3";
import * as yup from "yup";
import { ref } from "vue";

const props = defineProps({
    user: Object,
    list_role: Object,
    isCreate: Boolean,
    title: String,
});

const form_route = props.isCreate
    ? ref(route("admin.user.store"))
    : ref(route("admin.user.update", props.user?.slug));
const formDetail = {
    initialValues: {
        _method: props.isCreate ? "post" : "put",
        name: props.user?.name,
        email: props.user?.email,
        role: props.user?.roleSlug,
    },
    validationSchema: yup.object({
        name: yup.string().required("Name is required"),
        email: yup
            .string()
            .email("Invalid email")
            .required("Email is required"),
        role: yup.array().required("Role is required"),
    }),
};
const form = inertiaUseForm(formDetail.initialValues);
const formValidator = useVeeForm(formDetail);
const errors = formValidator.errors;
const [name] = formValidator.defineField("name");
const [email] = formValidator.defineField("email");
const [role] = formValidator.defineField("role");
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <FormCard
            :title="props.title"
            :form="form"
            :form_validator="formValidator"
            :back_route="route('admin.user.index')"
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
                    <label for="name">Email</label>
                    <InputText
                        type="text"
                        name="email"
                        v-model="email"
                        :invalid="errors.email || form?.errors?.email"
                        placeholder="Email"
                        fluid
                    />
                    <Message
                        v-if="errors.email || form?.errors?.email"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{ errors.email ?? form?.errors.email }}</Message
                    >
                </div>
                <div class="flex flex-col gap-1">
                    <label for="route">Role</label>
                    <MultiSelect
                        name="role"
                        :options="list_role"
                        v-model="role"
                        optionLabel="name"
                        optionValue="slug"
                        :invalid="errors.role || form?.errors?.role"
                        placeholder="Select a role"
                        fluid
                        filter
                        showClear
                    />
                    <Message
                        v-if="errors.role || form?.errors?.role"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{ errors.role ?? form?.errors.role }}</Message
                    >
                </div>
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>
