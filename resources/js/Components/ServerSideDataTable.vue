<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
    route: String,
});
const records = ref([]);
const search = ref("");
const totalRecords = ref(0);
const currentPage = ref(1);
const perPage = ref(10);
const sortField = ref(null);
const sortOrder = ref(1);
const perPageOptions = [5, 10, 25, 50];

const fetchData = async () => {
    const { data } = await axios.get(props.route, {
        params: {
            page: currentPage.value,
            perPage: perPage.value,
            sortField: sortField.value,
            sortOrder: sortOrder.value,
        },
    });

    records.value = data.data;
    totalRecords.value = data.totalRecords;
};

const onPageChange = (event) => {
    currentPage.value = event.page + 1;
    perPage.value = event.rows;
    fetchData();
};

const onSortChange = (event) => {
    sortField.value = event.sortField;
    sortOrder.value = event.sortOrder;
    fetchData();
};

const onPerPageDropdownChange = (event) => {
    perPage.value = event.value;
    currentPage.value = 1; // Reset to first page
    fetchData();
};

onMounted(fetchData);
</script>

<template>
    <div>
        <DataTable
            :lazy="true"
            :value="records"
            :paginator="true"
            :rows="perPage"
            :totalRecords="totalRecords"
            :first="(currentPage - 1) * perPage"
            :sortField="sortField"
            :sortOrder="sortOrder"
            @page="onPageChange"
            @sort="onSortChange"
            :rowsPerPageOptions="perPageOptions"
            paginatorTemplate="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
            currentPageReportTemplate="{first} to {last} of {totalRecords}"
        >
            <template #header>
                <div class="flex justify-start">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText
                            v-model="search"
                            placeholder="Keyword Search"
                        />
                    </IconField>
                </div>
            </template>

            <template #paginatorstart>
                <div class="p-d-flex p-ai-center p-ml-auto">
                    <label for="rows" class="p-mr-2">Rows per page:</label>
                    <Dropdown
                        inputId="rows"
                        :options="perPageOptions"
                        v-model="perPage"
                        @change="onPerPageDropdownChange"
                        style="width: 100px"
                    />
                </div>
            </template>

            <!-- <template #paginatorend>
                <RowsPerPageDropdown
                    :value="perPage"
                    :options="perPageOptions"
                    @change="onPerPageDropdownChange"
                />
            </template> -->

            <slot name="columns" />
        </DataTable>
    </div>
</template>
