import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

export default function usePrimevueHelpers() {
    const toast = useToast();
    const confirm = useConfirm();

    function showToast({
        severity = "success",
        summary = "",
        detail = "",
        life = 3000,
    }) {
        toast.add({
            severity,
            summary,
            detail,
            life,
        });
    }

    function showSaveConfirm({
        message,
        header = "Are you sure to save this information?",
        icon = "pi pi-exclamation-triangle",
        accept,
        reject,
    }) {
        confirm.require({
            message,
            header,
            icon,
            accept,
            reject,
            rejectClass: "p-button-secondary",
        });
    }

    function showUpdateConfirm({
        message,
        header = "Are you sure to update this information?",
        icon = "pi pi-exclamation-triangle",
        accept,
        reject,
    }) {
        confirm.require({
            message,
            header,
            icon,
            accept,
            reject,
            rejectClass: "p-button-secondary",
        });
    }

    function showDeleteConfirm({
        message,
        header = "Are you sure to delete this information?",
        icon = "pi pi-exclamation-triangle",
        accept,
        reject,
    }) {
        confirm.require({
            message,
            header,
            icon,
            accept,
            reject,
            rejectClass: "p-button-secondary",
        });
    }

    return {
        showToast,
        showSaveConfirm,
        showUpdateConfirm,
        showDeleteConfirm,
    };
}
