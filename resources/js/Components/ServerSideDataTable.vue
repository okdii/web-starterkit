<script setup>
import { ref, watch, onMounted } from "vue";
import { debounce } from "lodash";
import axios from "axios";

const props = defineProps({
    route: String,
    filter_form: Object,
    hasFilter: {
        default: false,
    },
});
const records = ref([]);
const keyword = ref("");
const totalRecords = ref(0);
const currentPage = ref(1);
const perPage = ref(10);
const sortField = ref(null);
const sortOrder = ref(1);
const perPageOptions = [5, 10, 25, 50];
const loading = ref(false);
const showFilterForm = ref(false);

const fetchData = async () => {
    loading.value = true;

    const { data } = await axios.post(props.route, {
        page: currentPage.value,
        perPage: perPage.value,
        sortField: sortField.value,
        sortOrder: sortOrder.value,
        keyword: keyword.value,
        filter: props.filter_form,
    });

    records.value = data.data;
    totalRecords.value = data.totalRecords;
};

const resetData = () => {
    keyword.value = "";
    props.filter_form.reset();
    fetchData();
};

const onSearch = debounce(fetchData, 500);

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

const hideShowFilter = () => {
    showFilterForm.value = !showFilterForm.value;
};

onMounted(fetchData);

defineExpose({
    refreshTable: () => fetchData(),
});
</script>

<template>
    <div>
        <DataTable
            :lazy="true"
            @lazyLoad="fetchData"
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
            removableSort
            scrollable
            scrollHeight="flex"
        >
            <template #empty> No record found. </template>
            <template #loading> Loading data. Please wait. </template>
            <template #header>
                <div class="flex justify-between mb-2">
                    <div>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText
                                v-model="keyword"
                                placeholder="Keyword Search"
                                @input="onSearch"
                            />
                        </IconField>
                    </div>
                    <div v-if="props.hasFilter && props.filter_form">
                        <Button
                            icon="pi pi-filter"
                            severity="secondary"
                            variant="outlined"
                            aria-label="Filter"
                            @click="hideShowFilter"
                        />
                    </div>
                </div>

                <div
                    v-show="showFilterForm"
                    class="bg-slate-100 w-full p-3 mb-3"
                >
                    <slot name="filter" />

                    <Divider />

                    <div class="flex justify-center gap-1">
                        <Button
                            type="reset"
                            severity="secondary"
                            label="Reset"
                            @click="resetData"
                        />
                        <Button
                            type="submit"
                            severity="primary"
                            label="Filter"
                            @click="fetchData"
                        />
                    </div>
                </div>
            </template>

            <template #paginatorend>
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

            <slot name="columns" />
        </DataTable>
    </div>
</template>
<style>
.p-datatable-header {
    padding: 0;
}
</style>
