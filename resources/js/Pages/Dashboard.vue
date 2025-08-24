<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import draggable from "vuedraggable";
import TreeNode from "@/Components/TreeNode.vue";

const treeData = ref([
    {
        id: 1,
        name: "Root Node",
        children: [],
    },
]);

const addRootNode = () => {
    treeData.value.push({
        id: Date.now(),
        name: "New Root",
        children: [],
    });
};

const deleteRootNode = (id) => {
    treeData.value = treeData.value.filter((n) => n.id !== id);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <BaseCard title="Dashboard">
            <template #content>
                <h3>Welcome to Dashboard</h3>
                <br />
                <Link
                    :href="route('admin.user.index')"
                    class="p-button p-component"
                >
                    List of Users
                </Link>
            </template>
        </BaseCard>

        <BaseCard title="Menu">
            <template #button>
                <div class="w-full xl:w-auto">
                    <Button
                        label="New"
                        icon="pi pi-plus"
                        iconPos="right"
                        @click="addRootNode"
                    />
                </div>
            </template>
            <template #content>
                <draggable
                    :list="treeData"
                    group="nodes"
                    item-key="id"
                    handle=".drag-handle"
                    animation="200"
                    class="space-y-2"
                >
                    <template #item="{ element }">
                        <TreeNode
                            :node="element"
                            :depth="0"
                            @delete-node="deleteRootNode"
                        />
                    </template>
                </draggable>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
