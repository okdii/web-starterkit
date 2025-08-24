<script setup>
import { ref, watch, useSlots, computed } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import draggable from "vuedraggable";
import TreeNode from "./TreeNode.vue";
import usePrimevueHelpers from "@/Helpers/Confirmation";
const { showToast, showDeleteConfirm } = usePrimevueHelpers();

const page = usePage();
const slots = useSlots();
const props = defineProps({
    node: Object,
    depth: {
        type: Number,
        default: 0,
    },
    onMove: Function,
    edit_route: String,
    delete_route: String,
});
const emit = defineEmits(["delete-node"]);

const node = props.node;
const isEditing = ref(false);
const isCollapsed = ref(false);

const hasChildren = computed(() => node.children?.length > 0);

const startEdit = () => {
    isEditing.value = true;
};

const saveEdit = () => {
    isEditing.value = false;
};

const addChild = () => {
    const newId = Date.now();
    node.children = node.children || [];
    node.children.push({
        slug: newId,
        name: "New Node",
        children: [],
    });
    isCollapsed.value = false;
};

const deleteChild = (slug) => {
    node.children = node.children.filter((c) => c.slug !== slug);
};

const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value;
};

function confirmDelete(deleteRoute) {
    showDeleteConfirm({
        accept: () => {
            useForm({}).delete(deleteRoute, {
                onFinish: () => {
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
    <div :style="{ marginLeft: `${depth * 1.25}rem` }" class="mb-2">
        <!-- Node UI -->
        <div
            class="flex items-center gap-2 p-2 rounded border border-gray-300 bg-white shadow-sm"
        >
            <!-- Collapse/Expand -->
            <button
                v-if="hasChildren"
                @click="toggleCollapse"
                class="w-4 text-gray-500 hover:text-gray-700 mx-3"
            >
                <span v-if="isCollapsed" class="pi pi-chevron-right"></span>
                <span v-else class="pi pi-chevron-down"></span>
            </button>
            <div v-else class="w-4"></div>

            <!-- Drag Handle -->
            <span
                class="cursor-move text-gray-400 hover:text-gray-600 drag-handle pi pi-bars mr-5"
            ></span>

            <!-- Name / Edit -->
            <InputText
                v-if="isEditing"
                type="text"
                v-model="node.name"
                @blur="saveEdit"
                @keyup.enter="saveEdit"
                class="w-full"
            />
            <span v-else class="text-gray-800 font-medium text-sm">
                <span
                    v-if="node.icon"
                    :class="[
                        'pi',
                        node.icon.replace('pi ', ''),
                        'text-gray-500',
                        'mr-2',
                    ]"
                />

                {{ node.name }}
            </span>

            <!-- Actions -->
            <!-- <div v-if="slots.buttons" class="ml-auto flex gap-1">
                <slot name="buttons"></slot>
            </div>
            <div v-else class="ml-auto flex gap-1">
                <Button
                    icon="pi pi-plus"
                    variant="text"
                    aria-label="Add"
                    v-tooltip.top="'Add'"
                    @click="addChild"
                />
                <Button
                    icon="pi pi-pencil"
                    variant="text"
                    aria-label="Edit"
                    v-tooltip.top="'Edit'"
                    @click="startEdit()"
                />
                <Button
                    icon="pi pi-trash"
                    variant="text"
                    aria-label="Delete"
                    v-tooltip.top="'Delete'"
                    severity="danger"
                    @click="$emit('delete-node', node.slug)"
                />
            </div> -->
            <div class="ml-auto flex gap-1">
                <Link class="w-full" :href="route(edit_route, node.slug)">
                    <Button
                        icon="pi pi-pencil"
                        variant="text"
                        aria-label="Edit"
                        v-tooltip.top="'Edit'"
                    />
                </Link>
                <Button
                    icon="pi pi-trash"
                    variant="text"
                    aria-label="Delete"
                    v-tooltip.top="'Delete'"
                    severity="danger"
                    @click="confirmDelete(route(delete_route, node.slug))"
                />
            </div>
        </div>

        <!-- Child Nodes -->
        <draggable
            v-if="node.children && !isCollapsed"
            :list="node.children"
            group="nodes"
            item-key="slug"
            handle=".drag-handle"
            animation="200"
            :move="onMove"
            class="pl-4 mt-1 space-y-2"
        >
            <template #item="{ element }">
                <TreeNode
                    :node="element"
                    :depth="depth + 1"
                    :on-move="onMove"
                    @delete-node="deleteChild"
                    :edit_route="edit_route"
                    :delete_route="delete_route"
                />
            </template>
        </draggable>
    </div>
</template>
