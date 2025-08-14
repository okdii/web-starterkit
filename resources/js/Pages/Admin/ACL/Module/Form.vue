<script setup>
import FormCard from "@/Components/FormCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { useForm as inertiaUseForm } from "@inertiajs/vue3";
import * as yup from "yup";
import { ref } from "vue";

const props = defineProps({
    module: Object,
    isCreate: Boolean,
    title: String,
});

const form_route = props.isCreate
    ? ref(route("admin.module.store"))
    : ref(route("admin.module.update", props.module?.slug));
const formDetail = {
    initialValues: {
        _method: props.isCreate ? "post" : "put",
        name: props.module?.name,
        description: props.module?.description,
    },
    validationSchema: yup.object({
        name: yup.string().required("Name is required"),
        description: yup.string().required("Description is required"),
    }),
};
const form = inertiaUseForm(formDetail.initialValues);
const formValidator = useVeeForm(formDetail);
const errors = formValidator.errors;
const [name] = formValidator.defineField("name");
const [description] = formValidator.defineField("description");
</script>

<template>
    <Head title="Module" />

    <AuthenticatedLayout>
        <FormCard
            :title="props.title"
            :form="form"
            :form_validator="formValidator"
            :back_route="route('admin.module.index')"
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
                    <label for="description">Description</label>
                    <Textarea
                        name="description"
                        v-model="description"
                        :invalid="
                            errors.description || form?.errors?.description
                        "
                        placeholder="Description"
                        rows="5"
                    />
                    <Message
                        v-if="errors.description || form?.errors?.description"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{
                            errors.description ?? form?.errors.description
                        }}</Message
                    >
                </div>
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>
