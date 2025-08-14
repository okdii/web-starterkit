<script setup>
import AppBar from "@/Components/AppBar.vue";
import SideMenu from "@/Components/SideMenu.vue";
import { ref } from "vue";

const drawerVisible = ref(false);
const toggleDrawer = () => {
    drawerVisible.value = true;
};
</script>
<template>
    <Toast />
    <ConfirmDialog />
    <div>
        <div>
            <AppBar @toggle-menu="toggleDrawer" />
            <SideMenu v-model:visible="drawerVisible" />
        </div>

        <div class="pb-4">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <slot />
            </div>
        </div>
    </div>
</template>
