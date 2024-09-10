<template>

    <Modal :show="isImportPopupOpen" @close="toggleImportPopup" :modalClose="toggleImportPopup">
        <input
            ref="fileInput"
            type="file"
            class="hidden"
            @change="importFile"
        />
        <div class="flex flex-row items-center justify-center" style="min-height: 250px">
            <button
                class="w-48 text-center text-sm font-semibold text-base transition ease-in-out duration-150 text-white bg-primary border border-primary rounded-lg px-7 py-2 cursor-pointer mr-2"
                @click.prevent="importFile"
            >
                Download Template
            </button>
            <button
                class="w-48 text-center text-sm font-semibold text-base transition ease-in-out duration-150 text-white bg-primary border border-primary rounded-lg px-7 py-2 cursor-pointer mr-2"
                @click.prevent="selectFile"
            >
                Import
            </button>
        </div>
    </Modal>
    <div class="flex flex-col md:flex-row justify-between filtersDiv pb-3">
        <!-- Search bar -->
        <div v-if="isEnableDataTableSearch" class="flex items-center">
            <input v-model="searchQuery"
                class="w-96 focus:outline-none text-sm font-semibold text-base text-primary bg-white border border-gary rounded-lg px-4 pt-2.5 pb-2.5 leading-pro pr-10"
                type="text" placeholder="Type here....." @keyup="handleKeyUp" />
        </div>
        <div class="flex items-center justify-end createBtns" v-if="hasCreate">
            <!-- Create button -->
            <Link :href="btnCreate" :class="itemClass" v-if="!hasModal">
            <button
                class="w-40 text-center text-sm font-semibold text-base transition ease-in-out duration-150 text-white bg-indigo-600 border border-primary rounded-lg px-4 pt-2.5 pb-2.5 cursor-pointer leading-pro">
                <i class="fa fa-plus mr-2"></i>
                Create
            </button>
            </Link>
            <!-- Create button -->
            <button v-if="hasModal" @click="showModal"
                class="w-40 text-center text-sm font-semibold text-base transition ease-in-out duration-150 text-white bg-indigo-600 border border-primary rounded-lg px-4 pt-2.5 pb-2.5 cursor-pointer leading-pro">
                <i class="fa fa-plus mr-2"></i>
                {{ modalButtonTitle }}
            </button> 
        </div>
    </div>
    <div class="flex flex-col sm:flex-row justify-between">
        <!-- Number field -->
        <div v-if="isEnableDataTableEntries" class="flex items-center">
            <label class="mr-2 text-sm text-black font-semibold">Show</label>
            <select
                class="w-6/12 text-sm focus:outline-none font-semibold text-base font-light text-primary bg-transparent border border-primary rounded-lg px-4 pt-2.5 pb-2.5 leading-pro"
                v-model="searchEntry"
                @change="handleSearchEntries"
            >
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label class="ml-2 text-sm text-black font-semibold">entries</label>
        </div>
        <div class="flex items-center">
            <!-- Import button -->
<!--            <button-->
<!--                class="text-center font-light text-sm text-base font-semibold text-primary bg-transparent border border-primary rounded-lg px-7 py-2 cursor-pointer mr-4 leading-pro hover:bg-purple-100"-->
<!--                @click="import"-->
<!--            >-->
<!--                <i class="fas fa-upload mr-2"></i>-->
<!--                Import-->
<!--            </button>-->

            <!-- Export button -->
            <div class="relative" ref="dropdown">
                <button
                    v-if="isEnableExport"
                    class="text-center font-light text-sm text-base font-semibold text-white bg-indigo-600 border border-primary rounded-lg px-4 pt-2.5 pb-2.5 mr-4 cursor-pointer leading-pro"
                    @click="toggleDropdown">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
                <div v-if="isDropdownOpen"
                     class="absolute right-0 w-40 mt-2 bg-white border border-primary rounded-lg shadow-md z-10"
                     @click.outside="closeDropdown"
                     >
                    <a
                        href="#"
                        class="block px-4 py-2 text-sm text-primary hover:bg-primary rounded-t font-semibold"
                        @click="exportDataWithType(filterData(), fileName, 'xls'); closeDropdown"
                    >
                        <i class="fas fa-file-excel mr-2"></i>
                        Export to Excel
                    </a>
                    <a v-if="isEnablePDFExport"
                        href="#"
                        class="block px-4 py-2 text-sm text-primary hover:bg-primary font-semibold"
                        @click="exportPDF(filterData(), fileName); closeDropdown"
                    >
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export to PDF
                    </a>
                    <a href="#"
                        class="block px-4 py-2 text-sm text-primary hover:bg-primary  rounded-b font-semibold"
                        @click="exportDataWithType(filterData(), fileName, 'csv'); closeDropdown"
                    >
                        <i class="fas fa-file-csv mr-2"></i>
                        Export to CSV
                    </a>
                </div>
            </div>

            <!-- Print button -->
            <button v-if="isEnablePrint"
                class="text-center font-light text-sm text-base  font-semibold text-white bg-indigo-600 border border-primary rounded-lg px-4 pt-2.5 pb-2.5 cursor-pointer leading-pro"
                v-print="printObj ">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>

            <!-- Delete button -->
            <button v-if="hasDeleteSelected "
                class="w-48 text-center font-light text-sm font-semibold text-base text-red-500 bg-transparent border border-red-500 rounded-lg px-4 pt-2.5 pb-2.5 cursor-pointer leading-pro hover:bg-red-500 hover:border-none hover:text-white"
                @click="deleteConfirmation(selectedIds, props.deleteSelectedRoute)">
                <i class="far fa-trash-alt mr-2"></i>
                Delete Selected
            </button>
        </div>
    </div>
</template>
<script setup>
import {Link, router, useForm} from '@inertiajs/vue3';
import {inject, onMounted, onUnmounted, ref, watchEffect, computed} from "vue";
import exportFromJSON from "export-from-json";
import JsPDF from 'jspdf';
import {applyPlugin} from 'jspdf-autotable'
import 'jspdf-autotable';
import {notify} from "notiwind";
import Modal from "./Modal.vue";

applyPlugin(JsPDF)

const emit = defineEmits(['keyup-search-text', 'search-entries', 'showModal','importResponse']);

const props = defineProps({
    hasCreate: {
        type: Boolean,
        default:true
    },
    btnCreate: {
        type: String
    },
    itemClass: {
        type: String,
    },
    hasDeleteSelected: {
        type: Boolean,
        default: false
    },
    printTitle: {
        type: String,
        default: 'HorseFit'
    },
    printId: {
        type: String,
        default: 'printMe'
    },
    hasModal: {
        type: Boolean,
        default: false
    },
    searchQuery: {
        type: String,
        default: ''
    },
    searchEntries: {
        default: 10
    },
    exportData: {
        type: Object,
        default: null,
    },
    importRoute: {
        type: String,
        default: null,
    },
    filterData: {
        type: Function,
        default: null,
    },
    route:{
        type: String,
        default: null,
    },
    fileName:{
        type: String,
        default: 'exported-data',
    },
    deleteSelectedRoute: {
        type: String,
        required: true,
    },
    selectedIds: {
        type: Object,
        default: null,
    },
    isEnableExport: {
        type: Boolean,
        default: true,
    },
    isEnablePrint: {
        type: Boolean,
        default: true,
    },
    isEnablePDFExport: {
        type: Boolean,
        default: true,
    },
    isEnableDataTableEntries: {
        type: Boolean,
        default: true,
    },
    isEnableDataTableSearch: {
        type: Boolean,
        default: true,
    },
    modalButtonTitle: {
        type: String,
        default: 'Create'
    },
})

const swal = inject('$swal')
const searchQuery = ref('');
const searchEntry = ref(null);

const handleKeyUp = () => {
    // Emit the keyup text to the parent component
    emit('keyup-search-text', searchQuery.value);
};
const handleSearchEntries = () => {
    // Emit the keyup text to the parent component
    emit('search-entries', searchEntry.value);
};

const showModal = () => {
    emit('showModal');
};

//export button dropdown
const isDropdownOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
};

const exportDataWithType = (data, newFileName, fileExportType) => {
    if (!data) return;
    try {
        const fileName = newFileName || "exported-data";
        const exportType = exportFromJSON.types[fileExportType || "xls"];
        exportFromJSON({data, fileName, exportType});
    } catch (e) {
        throw new Error("Parsing failed!");
    }
}

const exportPDF = (data, newFileName) => {


  const options = {
        filename:  newFileName || "exported-data"
    };
    
    const doc = new JsPDF();
    
    const tableHeaders = Object.keys(data[0]);
    const tableData = data.map(item => Object.values(item));
    
    doc.text(options.filename, 14, 16);
    
    doc.autoTable({
        head: [tableHeaders],
        body: tableData,
        startY: 20
    });
    
    doc.save(options.filename + '.pdf');


    // try {
    //     const fileName = newFileName || 'exported-data';
    //     const doc = new JsPDF();

    //     // Get the table element
    //     const tableElement = document.getElementById(props.printId);

    //     // Get the table columns
    //     const columns = Array.from(tableElement.querySelectorAll('thead th')).map((column) => column.innerText);

    //     // Get the table rows
    //     const rows = Array.from(tableElement.querySelectorAll('tbody tr')).map((row) =>
    //         Array.from(row.querySelectorAll('td')).map((cell) => cell.innerText)
    //     );
    //     columns.pop();
    //     rows.forEach((row) => row.pop());

    //     // Generate the table in the PDF document
    //     doc.autoTable({
    //         head: [columns], // Use the modified columns array
    //         body: rows // Use the modified rows array
    //     });

    //     doc.save(fileName + '.pdf');
    // } catch (e) {
    //     throw new Error('Parsing failed!');
    // }
}

const printObj = ref({
    id: props.printId,
    popTitle: props.printTitle,
})

const deleteConfirmation = (selectedIds, deleteSelectedRoute) => {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Yes, delete it!",
        customClass: {
            confirmButton: "inline-block px-6 py-3 m-2 ml-2 text-xs font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-primary shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85",
            cancelButton: "inline-block px-6 py-3 m-2 font-bold text-center uppercase align-middle transition-all bg-gray-200 border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 text-slate-800",
        },
        buttonsStyling: false,
    }).then((result) => {
        console.log(selectedIds);
        if (result.isConfirmed) {
            deleteItems(selectedIds, deleteSelectedRoute);
            notify({
                group: "success",
                title: "Success",
                text: "Successfully deleted!"
            }, 4000)
        } else if (
            result.dismiss === swal.DismissReason.cancel
        ) {
            swal.dismiss;
        }
    });
}

const deleteItems = (selectedIds, deleteSelectedRoute) => {
    router.delete(route(deleteSelectedRoute, {'selected' : selectedIds}));
}

onMounted(() => {
    searchEntry.value = props.searchEntries;
    searchQuery.value = props.searchQuery;
});


const form = useForm({

    _method: "POST",
    file: null,

});

const fileInput = ref(null);

const selectFile = () => {
    fileInput.value.click();
};

const errorBag = ref([]);
const importFile = () => {

    form.file = fileInput.value.files[0];

    try {
        form.post(route(props.importRoute), {
            preserveState: true,
            errorBag: 'importFile',
            onSuccess: (res) => {
                isImportPopupOpen.value = false;
                emit('importResponse', res);
                form.reset();
            },
            onError: (res) => {
                emit('importResponse', res);
                form.reset();
            }
        });
    } catch (e) {
        console.log(e);
    }
};

const isImportPopupOpen = ref(false);

const toggleImportPopup = () => {
    isImportPopupOpen.value = !isImportPopupOpen.value;
};

const showCreateModal = () => {
    isImportPopupOpen.value = true;
};

const hideCreateModal = () => {
    isImportPopupOpen.value = false
};

const dropdown = ref(null);

const clickOutsideEvent = (event) => {
    if (!(dropdown.value === event.target || dropdown.value.contains(event.target))) {
        closeDropdown();
    }
};

watchEffect(() => {
    if (isDropdownOpen.value) {
        document.body.addEventListener('click', clickOutsideEvent);
    } else {
        document.body.removeEventListener('click', clickOutsideEvent);
    }
});

onUnmounted(() => {
    document.body.removeEventListener('click', clickOutsideEvent);
});

function saveAs(pdfBlob, pdf) {
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(pdfBlob);
    link.download = pdf;
    link.click();
}

const generatePDF = async (route, fileName) => {
    axios.get(route, {
        responseType: 'blob',
    }).then((response) => {
        const pdfBlob = new Blob([response.data], { type: 'application/pdf' });
        saveAs(pdfBlob, fileName);
    }).catch((error) => {
        console.log(error)
    });
}
</script>