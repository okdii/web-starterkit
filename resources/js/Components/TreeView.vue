<script setup>
import { computed } from "vue";
import draggable from "vuedraggable";
import TreeNode from "./TreeNode.vue";

const props = defineProps({
    modelValue: Array,
});
const emit = defineEmits(["update:modelValue"]);

const tree = computed({
    get: () => props.modelValue,
    set: (val) => emit("update:modelValue", val),
});

const addRootNode = () => {
    tree.value.push({
        id: Date.now(),
        name: "New Root",
        children: [],
    });
};

const deleteNode = (id, nodes = tree.value) => {
    for (let i = nodes.length - 1; i >= 0; i--) {
        if (nodes[i].id === id) {
            nodes.splice(i, 1);
        } else if (nodes[i].children) {
            deleteNode(id, nodes[i].children);
        }
    }
};

const deleteRootNode = (id) => {
    treeData.value = treeData.value.filter((n) => n.id !== id);
};
</script>
<template>
    <div class="flex justify-end">
        <Button
            label="New"
            icon="pi pi-plus"
            iconPos="right"
            @click="addRootNode"
        />
    </div>
    <draggable
        :list="tree"
        group="nodes"
        item-key="id"
        handle=".drag-handle"
        animation="200"
        class="space-y-2"
    >
        <template #item="{ element }">
            <TreeNode :node="element" :depth="0" @delete-node="deleteNode" />
        </template>
    </draggable>
</template>
