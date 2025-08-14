<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { useSlots } from "vue";
import usePrimevueHelpers from "@/Helpers/Confirmation";

const page = usePage();
const { showToast, showSaveConfirm, showUpdateConfirm } = usePrimevueHelpers();
const slots = useSlots();
const props = defineProps({
    title: String,
    form: Object,
    form_validator: Object,
    form_route: String,
    back_route: String,
});

const submit = props.form_validator.handleSubmit(async (values) => {
    Object.entries(values).forEach((field) => {
        const [key, value] = field;
        props.form[key] = value;
    });

    if (props.isCreate) {
        showSaveConfirm({
            accept: () => {
                props.form.post(props.form_route, {
                    onFinish: () => {
                        if (page.props.flash) {
                            showToast(page.props.flash);
                        }
                    },
                });
            },
        });
    } else {
        showUpdateConfirm({
            accept: () => {
                props.form.post(props.form_route, {
                    onFinish: () => {
                        if (page.props.flash) {
                            showToast(page.props.flash);
                        }
                    },
                });
            },
        });
    }
});
</script>
<template>
    <Card class="my-3">
        <template #title>
            <div class="flex flex-wrap content-center justify-between">
                <div class="p-1">
                    <p class="font-bold text-lg">{{ props.title }}</p>
                </div>
                <div
                    v-if="slots.button"
                    class="flex flex-row flex-wrap justify-center gap-1 pa-1 mx-1 w-full xl:w-auto"
                >
                    <slot name="button"></slot>
                </div>
            </div>
            <Divider />
        </template>
        <template #content>
            <div class="px-2">
                <form
                    @submit.prevent="submit"
                    class="flex flex-col gap-4 w-full"
                >
                    <slot name="content"></slot>

                    <div class="flex flex-row justify-between mt-3">
                        <div>
                            <Link :href="props.back_route" class="w-1/5">
                                <Button severity="danger"> Back </Button>
                            </Link>
                        </div>

                        <div class="flex gap-1">
                            <Button
                                type="reset"
                                severity="secondary"
                                label="Reset"
                            />
                            <Button
                                type="submit"
                                severity="primary"
                                label="Submit"
                                :loading="props.form_validator.processing"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </template>
        <template #footer> </template>
    </Card>
</template>
