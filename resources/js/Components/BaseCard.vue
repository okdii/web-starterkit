<script setup>
import { Link } from "@inertiajs/vue3";
import { useSlots } from "vue";

const slots = useSlots();
const props = defineProps({
    title: String,
    back_route: String,
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
                <slot name="content"></slot>
            </div>
        </template>
        <template #footer>
            <div
                class="flex flex-row flex-wrap gap-1 w-full mt-3"
                v-if="props.back_route"
            >
                <div class="flex w-full xl:w-1/3">
                    <Link :href="back_route" class="w-full xl:w-1/5">
                        <Button severity="danger" class="w-full">Back</Button>
                    </Link>
                </div>
            </div>
        </template>
    </Card>
</template>
