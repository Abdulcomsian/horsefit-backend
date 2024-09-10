<template>
  <AuthenticatedLayout title="Roles">

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
    </template>
    <Modal :show="showModalView" :max-width="maxWidth" @close="showModalView = false" class="w-full">
      <ShowRoleModal
          :selectedViewRole="selectedViewRole"
          @close-modal="showModalView = false"
      />
    </Modal>


      <Container>
        <Filters
            @keyup-search-text="handleKeyUpText"
            @search-entries="handleSearchEntries"
            print-title="Print Role Table"
            print-id="printRoleTable"
            :btnCreate="route('roles.create')"
            :export-data="roles.data"
            :search-query="requestParam === null ? '' : requestParam.search"
            :search-entries="requestParam === null ? 10 : requestParam.entries"
            :filterData="filterData"
            :file-name="'Roles'"
            :has-create="$page.props.permissions.includes('Create Role')"
            :is-enable-export="$page.props.permissions.includes('Export Role')"
            :is-enable-print="$page.props.permissions.includes('Print Role')"
        />
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3" id="printRoleTable">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">Name</th>
              <th scope="col" class="px-6 py-3">Description</th>
              <th scope="col" class="px-6 py-3">Created at</th>
              <th scope="col" class="px-6 py-3">Status</th>
              <th scope="col" class="px-6 py-3 hide-on-print">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="roles.data.length === 0">
              <td colspan="5" class="text-center">No records found</td>
            </tr>
            <tr v-else v-for="role in roles.data" :key="role.id"
                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
              <td class="px-6 py-4 dark:text-white">{{ role.name }}</td>
              <td class="px-6 py-4 dark:text-white truncate overflow-hidden tbl-overflow-hidden">{{
                  role.description
                }}
              </td>
              <td class="px-6 py-4 dark:text-white">
                {{ dayjs(role.created_at).format('dddd, MMMM d, YYYY') }}
              </td>
              <td class="px-6 py-4 dark:text-white text-start">
                <Status :status="changeStatus(role.status)"/>
              </td>
              <td class="dark:text-white hide-on-print">
                <button
                  v-if="$page.props.permissions.includes('View Role')"
                    type="button"
                    class="w-10 focus:ring-1 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-2 ps-2.5 pe-2.5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none"
                    @click="showRoleDetails(role)"
                >
                  <i class="fas fa-eye text-slate-400 dark:text-white/70"></i>
                </button>
                <Link v-if="$page.props.permissions.includes('Edit Role')" :href="route('roles.edit', {'id': role.id})"
                      class="w-10 mx-2 focus:ring-1 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-2 ps-2.5 pe-2 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none">
                  <i class="fas fa-user-edit text-slate-400 dark:text-white/70"></i>
                </Link>

                <button
                v-if="$page.props.permissions.includes('Delete Role')"
                    type="button"
                    class="w-10 focus:ring-1 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-2 ps-2.5 pe-2.5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none"
                    @click="deleteConfirmation(role)"
                >
                  <i class="fas fa-trash text-slate-400 dark:text-white/70"></i>
                </button>
              </td>
            </tr>

            </tbody>
          </table>
        </div>
        <Pagination :data-object="roles"/>
      </Container>

  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {inject, ref, watch, onMounted} from "vue";
import {router, useForm, Link} from "@inertiajs/vue3";
import {notify} from "notiwind";
import Filters from "@/Components/Filters.vue";
import Pagination from "@/Components/Pagination.vue";
import Container from "@/Components/Container.vue";
import Status from "@/Components/Status.vue";
import ShowRoleModal from "./ShowRoleModal.vue";
import Modal from "@/Components/Modal.vue";
import dayjs from "dayjs";

const swal = inject('$swal');
const showModalView = ref(false);
const selectedViewRole = ref(null);

const showRoleDetails = (item) => {
  selectedViewRole.value = item;
  showModalView.value = true;
};

const filteredTypes = ref([]);

const searchText = ref('');
const searchEntries = ref(10);

const handleKeyUpText = (text) => {
  searchText.value = text;
};
const handleSearchEntries = (entries) => {
  searchEntries.value = entries;
};

const deleteForm = useForm({
  _method: 'DELETE',
});

const props = defineProps({
  roles: {
    type: Object,
    default: null,
  },
  requestParam: {
    type: Object,
    default: {
      'search': '',
      'entries': 10,
      'page': 1
    }
  },
  maxWidth: {
    type: String,
    default: 'xl',
  },
});

watch(searchText, async () => {
  await filterText();
});

watch(searchEntries, async () => {
  await filterEntries();
});


const filterText = async () => {
  router.get(route('roles.index'), {
    'search': searchText.value,
    'entries': searchEntries.value,

    'page': (props.requestParam && props.requestParam.page) ? props.requestParam.page : 1
  }, {
    preserveState: true,
    onError: (error) => {
      notify({
        group: "error",
        title: "Error",
        text: error
      }, 4000)
    }
  })
}
const filterEntries = async () => {
  router.get(route('roles.index'), {
    'search': searchText.value,
    'entries': searchEntries.value,

    'page': (props.requestParam && props.requestParam.page) ? props.requestParam.page : 1
  }, {
    preserveState: true,
    onError: (error) => {
      notify({
        group: "error",
        title: "Error",
        text: error
      }, 4000)
    }
  })
}

const deleteConfirmation = (item) => {
  swal({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    confirmButtonText: "Yes, delete it!",
    customClass: {
      confirmButton: "inline-block mr-5 mb-5 px-6 py-3 m-0 ml-2 text-xs font-bold text-center text-red-500 uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-primary shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85",
      cancelButton: "inline-block px-6 mb-5 py-3 m-0 font-bold text-center uppercase align-middle transition-all bg-gray-200 border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 text-slate-800",
    },
    buttonsStyling: false,
  }).then((result) => {
    if (result.isConfirmed) {
      deleteRole(item);
    } else if (
        result.dismiss === swal.DismissReason.cancel
    ) {
      swal.dismiss;
    }
  });
}

const deleteRole = (item) => {

  try {

    deleteForm.delete(route('roles.destroy', {id: item.id}), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        router.visit(route('roles.index'), {
          preserveState: true,
          preserveScroll: true,
          onSuccess: () => {
            notify(
                {
                  group: 'success',
                  title: 'Success',
                  text: 'Role successfully deleted!',
                }, 4000);
          },
        });
      },
      onError: (error) => {
        notify(
            {
              group: 'error',
              title: 'Error',
              text: 'Something went wrong!',
            }, 4000);
      },
    });
  } catch (error) {

    console.log(error);
    if (error.response.status === 422) {
      notify(
          {
            group: 'error',
            title: 'Error',
            text: 'Something went wrong!',
          }, 4000);
    }
  }
};

const changeStatus = (statusCode) => {
  switch (statusCode) {
    case 1:

      return "Active"
    case 2:
      return "Inactive"
    case 3:
      return "Blocked"
    default:
      return "Active"
  }
}

const filterData = () => {
  // filter data to export
  filteredTypes.value = props.roles.data.map((role, index) => {
    return {
      No: index + 1,
      Name: role.name,
      Description: role.description,
      'Created At':  role.created_at ? dayjs(role.created_at).format('dddd, MMMM d, YYYY')  : ''
    };
  });
  return filteredTypes.value;
}


</script>

<style scoped>
@media print {
  .hide-on-print {
    display: none;
  }
}
</style>