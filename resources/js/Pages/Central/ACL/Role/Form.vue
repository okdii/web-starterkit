<script setup>
import FormCard from "@/Components/FormCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { useForm as inertiaUseForm } from "@inertiajs/vue3";
import * as yup from "yup";
import { ref } from "vue";

const props = defineProps({
    role: Object,
    isCreate: Boolean,
    title: String,
});

const form_route = props.isCreate
    ? ref(route("central.role.store"))
    : ref(route("central.role.update", props.role?.slug));
const formDetail = {
    initialValues: {
        _method: props.isCreate ? "post" : "put",
        name: props.role?.name,
    },
    validationSchema: yup.object({
        name: yup.string().required("Name is required"),
    }),
};
const form = inertiaUseForm(formDetail.initialValues);
const formValidator = useVeeForm(formDetail);
const errors = formValidator.errors;
const [name] = formValidator.defineField("name");
</script>

<template>
    <Head title="Role" />

    <AuthenticatedLayout>
        <FormCard
            :title="props.title"
            :form="form"
            :form_validator="formValidator"
            :back_route="route('central.role.index')"
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
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>
