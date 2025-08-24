<script setup>
import { ref, watch } from "vue";
import { PrimeIcons } from "@primevue/core/api";
console.log(PrimeIcons);

const props = defineProps({
    modelValue: String,
    invalid: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const selectedIcon = ref(props.modelValue);

watch(selectedIcon, (newIcon) => {
    emit("update:modelValue", newIcon);
});

const icons = Object.entries(PrimeIcons).map(([key, value]) => {
    // Format label nicely: e.g. "USER_CIRCLE" → "User Circle"
    const label = key
        .toLowerCase()
        .split("_")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");

    return { label, value };
});
</script>

<template>
    <Dropdown
        name="icon"
        v-model="selectedIcon"
        :options="icons"
        optionLabel="label"
        :invalid="props.invalid"
        placeholder="Select Icon"
        fluid
        filter
        showClear
    >
        <template #value="slotProps">
            <div v-if="slotProps.value" class="flex items-center gap-2">
                <i :class="`pi ${slotProps.value.value}`" />
                <span>{{ slotProps.value.label }}</span>
            </div>
            <span v-else class="text-gray-400">No icon selected</span>
        </template>

        <template #option="slotProps">
            <div class="flex items-center gap-2">
                <i :class="`pi ${slotProps.option.value}`" />
                <span>{{ slotProps.option.label }}</span>
            </div>
        </template>
    </Dropdown>
</template>
