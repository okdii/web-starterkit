<script setup>
import { Link, useForm, usePage } from "@inertiajs/vue3";
import usePrimevueHelpers from "@/Helpers/Confirmation";
const { showToast, showDeleteConfirm } = usePrimevueHelpers();

const page = usePage();
const props = defineProps({
    item: Object,
    show_route: String,
    edit_route: String,
    delete_route: String,
});

function confirmDelete(deleteRoute) {
    showDeleteConfirm({
        accept: () => {
            useForm({}).delete(deleteRoute, {
                onFinish: () => {
                    // datatable.value.fetchData();
                    if (page.props.flash) {
                        showToast(page.props.flash);
                    }
                },
            });
        },
    });
}
</script>
<template>
    <div class="flex gap-1">
        <slot />
        <Link v-if="props.item.action?.show" :href="props.show_route">
            <Button
                icon="pi pi-file"
                variant="outlined"
                aria-label="Show"
                v-tooltip.top="'Detail'"
            />
        </Link>
        <Link v-if="props.item.action?.edit" :href="props.edit_route">
            <Button
                icon="pi pi-pencil"
                variant="outlined"
                aria-label="Edit"
                v-tooltip.top="'Edit'"
            />
        </Link>
        <Button
            v-if="props.delete_route"
            icon="pi pi-trash"
            variant="outlined"
            aria-label="Delete"
            severity="danger"
            @click="confirmDelete(props.delete_route)"
            v-tooltip.top="'Delete'"
        />
    </div>
</template>
