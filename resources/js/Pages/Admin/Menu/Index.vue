<script setup>
import BaseCard from "@/Components/BaseCard.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { useForm as inertiaUseForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import draggable from "vuedraggable";
import TreeNode from "@/Components/TreeNode.vue";
import usePrimevueHelpers from "@/Helpers/Confirmation";

const { showToast, showUpdateConfirm } = usePrimevueHelpers();
const page = usePage();
const props = defineProps({
    treeData: Object,
});
const MAX_DEPTH_ALLOWED = 2;

const formDetail = {
    initialValues: {
        _method: "post",
        treeData: props.treeData ?? [],
    },
};
const form = inertiaUseForm(formDetail.initialValues);
const formValidator = useVeeForm(formDetail);
const [treeData] = formValidator.defineField("treeData");
const submit = formValidator.handleSubmit(async (values) => {
    // formValidator.tree = treeData;
    showUpdateConfirm({
        accept: () => {
            form.post(route("admin.ajax.menu.update-tree"), {
                onFinish: () => {
                    if (page.props.flash) {
                        showToast(page.props.flash);
                    }
                },
            });
        },
    });
});

function getSubtreeDepth(node) {
    if (!node.children || node.children.length === 0) return 0;
    return 1 + Math.max(...node.children.map(getSubtreeDepth));
}

function findParentNode(nodes, targetList) {
    for (const node of nodes) {
        if (node.children === targetList) return node;
        if (node.children) {
            const found = findParentNode(node.children, targetList);
            if (found) return found;
        }
    }
    return null;
}

function getNodeDepthFromRoot(node, current = 0) {
    if (!node || !node._parent) return current;
    return getNodeDepthFromRoot(node._parent, current + 1);
}

const onMove = (evt) => {
    const draggedNode = evt.draggedContext.element;
    const draggedSubtreeDepth = getSubtreeDepth(draggedNode);

    // Find the parent node of the target list (where dragged node will be dropped)
    const targetList = evt.relatedContext.list;
    const targetParentNode = findParentNode(treeData, targetList);

    // If dropping at root, parent depth = -1
    const targetParentDepth = targetParentNode
        ? getNodeDepthFromRoot(targetParentNode)
        : -1;

    // Calculate the total depth after drop
    const totalDepth = targetParentDepth + 1 + draggedSubtreeDepth;

    if (totalDepth > MAX_DEPTH_ALLOWED) {
        return false;
    }

    return true;
};

const attachParentRefs = (nodes, parent = null) => {
    nodes.forEach((node) => {
        node._parent = parent;
        if (node.children) {
            attachParentRefs(node.children, node);
        }
    });
};

watch(
    treeData,
    () => {
        attachParentRefs(treeData.value);
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <Head title="Menu" />

    <AuthenticatedLayout>
        <BaseCard title="Menu">
            <template #button>
                <div class="w-full xl:w-auto">
                    <Link class="w-full" :href="route('admin.menu.create')">
                        <Button label="New" icon="pi pi-plus" iconPos="right" />
                    </Link>
                </div>
            </template>
            <template #content>
                <draggable
                    :list="treeData"
                    group="nodes"
                    item-key="slug"
                    handle=".drag-handle"
                    animation="200"
                    :move="onMove"
                    class="space-y-2"
                >
                    <template #item="{ element }">
                        <TreeNode
                            :node="element"
                            :depth="0"
                            :on-move="onMove"
                            edit_route="admin.menu.edit"
                            delete_route="admin.menu.destroy"
                        />
                    </template>
                </draggable>
            </template>
            <template #footer_button>
                <form
                    @submit.prevent="submit"
                    class="flex flex-col gap-4 w-full"
                >
                    <div
                        class="flex flex-row flex-wrap justify-center gap-1 w-full mt-3"
                    >
                        <Button
                            type="submit"
                            severity="primary"
                            label="Save"
                            :loading="formValidator.processing"
                        />
                    </div>
                </form>
            </template>
        </BaseCard>
    </AuthenticatedLayout>
</template>
