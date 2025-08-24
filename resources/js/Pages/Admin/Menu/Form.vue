<script setup>
import FormCard from "@/Components/FormCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import IconPicker from "@/Components/IconPicker.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { useForm as inertiaUseForm } from "@inertiajs/vue3";
import * as yup from "yup";
import { ref } from "vue";

const props = defineProps({
    menu: Object,
    isCreate: Boolean,
    title: String,
    list_route: Object,
});

const form_route = props.isCreate
    ? ref(route("admin.menu.store"))
    : ref(route("admin.menu.update", props.menu?.slug));
const formDetail = {
    initialValues: {
        _method: props.isCreate ? "post" : "put",
        name: props.menu?.name,
        route_name: props.menu?.route,
        icon: props.menu?.icon,
    },
    validationSchema: yup.object({
        name: yup.string().required("Name is required"),
        route_name: yup.object().required("Route is required"),
        icon: yup.object().required("Icon is required"),
    }),
};
const form = inertiaUseForm(formDetail.initialValues);
const formValidator = useVeeForm(formDetail);
const errors = formValidator.errors;
const [name] = formValidator.defineField("name");
const [route_name] = formValidator.defineField("route_name");
const [icon] = formValidator.defineField("icon");
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <FormCard
            :title="props.title"
            :form="form"
            :form_validator="formValidator"
            :back_route="route('admin.menu.index')"
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
                    <label for="route">Route Name</label>
                    <Select
                        name="route.name"
                        :options="list_route"
                        v-model="route_name"
                        optionLabel="name"
                        :invalid="errors.route_name || form?.errors?.route_name"
                        placeholder="Select a route"
                        fluid
                        filter
                        showClear
                    />
                    <Message
                        v-if="errors.route_name || form?.errors?.route_name"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{
                            errors.route_name ?? form?.errors.route_name
                        }}</Message
                    >
                </div>
                <div class="flex flex-col gap-1">
                    <label for="route">Icon</label>
                    <IconPicker v-model="icon" />
                    <Message
                        v-if="errors.icon || form?.errors?.icon"
                        severity="error"
                        size="small"
                        variant="simple"
                        >{{ errors.icon ?? form?.errors.icon }}</Message
                    >
                </div>
            </template>
        </FormCard>
    </AuthenticatedLayout>
</template>
