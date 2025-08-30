<script setup>
import SidebarMenuItem from "@/Components/Menu/SidebarMenuItem.vue";
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    menu: {
        type: Object,
        required: true,
    },
});

const open = ref(false);
const toggle = () => {
    if (hasChildren.value) open.value = !open.value;
};

const hasChildren = computed(
    () => props.menu.children && props.menu.children.length > 0
);
</script>
<template>
    <li>
        <Link
            v-if="props.menu.permission"
            v-ripple
            :href="route(menu.permission)"
            class="flex items-center cursor-pointer p-4 rounded text-surface-700 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800 duration-150 transition-colors p-ripple"
        >
            <i :class="['mr-6', menu.icon]"></i>
            <span class="font-medium">{{ menu.name }}</span>
        </Link>
        <a
            v-else
            v-ripple
            @click="toggle"
            class="flex items-center cursor-pointer p-4 rounded text-surface-700 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800 duration-150 transition-colors p-ripple"
        >
            <i :class="['mr-6', menu.icon]"></i>
            <span class="font-medium">{{ menu.name }}</span>
            <i
                v-if="hasChildren"
                class="pi pi-chevron-down ml-auto"
                :class="{ 'rotate-180': open }"
            ></i>
        </a>

        <Transition
            enter-active-class="transition-all duration-300 ease-in-out"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-[500px] opacity-100"
            leave-active-class="transition-all duration-300 ease-in-out"
            leave-from-class="max-h-[500px] opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <ul
                v-if="hasChildren"
                v-show="open"
                class="list-none py-0 pl-4 pr-0 m-0 overflow-y-hidden transition-all duration-[400ms] ease-in-out"
            >
                <SidebarMenuItem
                    v-for="(child, index) in menu.children"
                    :key="index"
                    :menu="child"
                />
            </ul>
        </Transition>
    </li>
</template>
