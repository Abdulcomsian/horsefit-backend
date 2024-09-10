<template>

  <AuthenticatedLayout title="Create Role">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Role</h2>
    </template>


    <Container>


      <FormSectionCustom @submitted="updateRole">
        <template #form>

          <div class="w-full bg-white p-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
              <div class="">
                <InputLabel value="Name*"/>
                <TextInput
                    id="key"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="name"
                    placeholder="eg. Admin"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.name"
                />
              </div>
              <div class="">
                <InputLabel value="Status*"/>
                <InputSelect
                    :options="statusList"
                    placeholder="Choose a status"
                    v-model="form.status"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.status"/>
              </div>
            </div>
            <div>
              <InputLabel value="Description*"/>
              <Textarea
                  id="description"
                  v-model="form.description"
                  type="text"
                  class="mt-1 block w-full"
                  autofocus
                  autocomplete="description"
                  placeholder="Please enter description"
              />
              <InputError class="mt-2" :message="form.errors.description"/>
            </div>

          </div>

          <div class="w-full bg-white p-3 mt-4">
            <h2 class="font-semibold col-span-3 text-xl text-gray-800 leading-tight">Permissions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">

              <div v-for="module in modules" :key="module.id" class="div-margin">
                <div class="col-span-1">
                  <h6 class="mb-3"><b>{{ module.name }}</b></h6>


                  <div v-for="permission in module.permissions" :key="permission.id" class="flex items-center mb-1">
                    <Checkbox :id="permission.id" v-model="selectedPermissions" :checked="selectedPermissions" :value="permission.id"/>&nbsp;
                    <label :for="permission.id" class="ms-1 text-sm font-medium text-gray-900 dark:text-gray-300">{{ permission.name }}</label>
                  </div>

                </div>
              </div>

            </div>

          </div>

          <div class="flex justify-end mt-6">
            <Link :href="route('roles.index')">
              <button
                  type="button"
                  class="inline-block px-6 py-3 m-0 font-bold text-center uppercase align-middle transition-all bg-gray-200 border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 text-slate-800"
              >
                Cancel
              </button>
            </Link>
            <PrimaryButton
                btnClass="btn-primary px-4 transition ease-in-out duration-150 ms-3"
                :class="{ 'opacity-25': form.processing || !isFormDirty }"
                :disabled="form.processing || !isFormDirty">
              Update Role
            </PrimaryButton>
          </div>
        </template>
      </FormSectionCustom>


    </Container>


  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, useForm, router} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Container from "@/Components/Container.vue";
import FormSectionCustom from "@/Components/FormSectionCustom.vue";
import {computed, onMounted, ref} from "vue";
import {notify} from "notiwind";
import Checkbox from "@/Components/Checkbox.vue";
import InputSelect from "@/Components/InputSelect.vue";
import Textarea from "@/Components/TextArea.vue";


const props = defineProps({
  modules: {
    type: Object,
    required: true
  },
  role: {
    type: Object,
    required: true
  },
  permissionIds: {
    type: Array,
    required: true
  }
});

const form = useForm({
  _method: 'PUT',
  name: props.role.name,
  description: props.role.description,
  permissions: [],
  status: props.role.status,
});

const isFormDirty = computed(() => {
  return (
      form.name !== props.role.name ||
      form.description !== props.role.description ||
      JSON.stringify(selectedPermissions.value) !== JSON.stringify(props.permissionIds) ||
      form.status !== props.role.status
  );
});

const statusList = ref([
  {id: 1, name: 'Active'},
  {id: 2, name: 'Inactive'}
]);

const selectedPermissions = ref([]);

const updateRole = async () => {
  form.permissions = selectedPermissions.value;
  axios.post(route('roles.update', props.role.id), form)
      .then(response => {
        router.visit(route('roles.index', {'entries': 10, 'page': 1}), {
          onSuccess: () => {
            notify({
              group: "success",
              title: "Success",
              text: "Role successfully updated!"
            }, 4000)
          }
        })
      })
      .catch(error => {
        form.processing = false;
        if (error.response.data.errors) {
          const errorFields = ['name', 'description', 'permissions', 'status'];
          errorFields.forEach(field => {
            form.errors[field] = error.response.data.errors[field] ? error.response.data.errors[field][0] : null;
          });
        }

        if (error.response.status === 422) {
          notify({
            group: "error",
            title: "Error",
            text: 'Something went wrong!'
          }, 4000);
        } else {

          notify({
            group: "error",
            title: "Error",
            text: error.message
          }, 4000);

        }

      });

}

onMounted(() => {
  selectedPermissions.value = props.permissionIds;
})
</script>

<style>
.div-margin {
  margin-top:20px;
}
</style>