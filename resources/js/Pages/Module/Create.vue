<template>
  <AuthenticatedLayout title="Create Module">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Module</h2>
      </template>

    <Container>
        <FormSectionCustom @submitted="createNewModule" class="">
          <template #form>

              <div class="w-full bg-white p-3">
                <div class="w-full">
                  <InputLabel value="Name*"/>
                  <TextInput
                      id="name"
                      v-model="form.name"
                      type="text"
                      class="mt-1 block w-full"
                      autofocus
                      autocomplete="name"
                      placeholder="eg. Dashboard"
                  />
                  <InputError class="mt-2" :message="form.errors.name ? form.errors.name[0] : '' "/>
                </div>
              </div>
              <CloneComponent
                v-slot="{ inputData, addInputField, removeInputField }"
                :itemArray="[]"
                @clone-input-data="cloneInputData"
            >
            <div v-for="(input, index) in inputData" :key="index">
                <div class="flex justify-end">
                  <div class="bg-gray-100 rounded-2xl mt-2 mb-2 w-full">
                    <h5
                    class="font-bold leading-6 capitalize mt-3 text-base text-[#8D1CBA]"
                    :id="input.name + '_' + input.id"
                    >
                    {{ 'Permission name ' + ( index + 1 ) }}
                    </h5>
                    <div class="w-4/5">
                        <div class="">
                            
                            <TextInput
                            id="'name' + index"
                            autocomplete="name"
                            v-model="input.name"
                            @input="updateInputField(index, input)"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Enter Permission Name"
                            />
                            <InputError
                            class="block mt-2 error"
                            :message="form.errors['permissions.' + index + '.name'] ? form.errors['permissions.' + index + '.name'][0] : ''"
                            />
                            <div class="ml-[20px] flex justify-end gap-1 mt-2 flex items-center">
                              <button
                              type="button"
                              class="text-xs text-gray-500 hover:text-gray-700 flex items-center transition-opacity duration-300"
                              @click.prevent="addInputField"
                              >
                              <svg
                                  width="24"
                                  height="24"
                                  class="h-[24px]"
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                              >
                                  <path
                                  d="M12 0C5.37097 0 0 5.37097 0 12C0 18.629 5.37097 24 12 24C18.629 24 24 18.629 24 12C24 5.37097 18.629 0 12 0ZM18.9677 13.3548C18.9677 13.6742 18.7065 13.9355 18.3871 13.9355H13.9355V18.3871C13.9355 18.7065 13.6742 18.9677 13.3548 18.9677H10.6452C10.3258 18.9677 10.0645 18.7065 10.0645 18.3871V13.9355H5.6129C5.29355 13.9355 5.03226 13.6742 5.03226 13.3548V10.6452C5.03226 10.3258 5.29355 10.0645 5.6129 10.0645H10.0645V5.6129C10.0645 5.29355 10.3258 5.03226 10.6452 5.03226H13.3548C13.6742 5.03226 13.9355 5.29355 13.9355 5.6129V10.0645H18.3871C18.7065 10.0645 18.9677 10.3258 18.9677 10.6452V13.3548Z"
                                  fill="#8D1CBA"
                                  />
                              </svg>
                              </button>
                              <button type="button"
                                  v-if="index == 0"
                                  class="text-xs text-gray-500 hover:text-gray-700 ml-2 flex items-center transition-opacity duration-300"
                                  :disabled="'disabled'"
                              >
                                  <svg
                                      width="24"
                                      height="24"
                                      viewBox="0 0 24 24"
                                      fill="none"
                                      xmlns="http://www.w3.org/2000/svg"
                                  >
                                      <path
                                      d="M12 0C5.37097 0 0 5.37097 0 12C0 18.629 5.37097 24 12 24C18.629 24 24 18.629 24 12C24 5.37097 18.629 0 12 0ZM5.6129 13.9355C5.29355 13.9355 5.03226 13.6742 5.03226 13.3548V10.6452C5.03226 10.3258 5.29355 10.0645 5.6129 10.0645H18.3871C18.7065 10.0645 18.9677 10.3258 18.9677 10.6452V13.3548C18.9677 13.6742 18.7065 13.9355 18.3871 13.9355H5.6129Z"
                                      fill="#8D1CBA"
                                      />
                                  </svg>
                              </button>
                              <button type="button"
                                  v-else
                                  class="text-xs text-gray-500 hover:text-gray-700 ml-2 flex items-center transition-opacity duration-300"
                                  @click.prevent="removeInputField(index, input.id, input.name)"
                                  :disabled="isRemoveButtonDisabled"
                              >
                                  <svg
                                      width="24"
                                      height="24"
                                      viewBox="0 0 24 24"
                                      fill="none"
                                      xmlns="http://www.w3.org/2000/svg"
                                  >
                                      <path
                                      d="M12 0C5.37097 0 0 5.37097 0 12C0 18.629 5.37097 24 12 24C18.629 24 24 18.629 24 12C24 5.37097 18.629 0 12 0ZM5.6129 13.9355C5.29355 13.9355 5.03226 13.6742 5.03226 13.3548V10.6452C5.03226 10.3258 5.29355 10.0645 5.6129 10.0645H18.3871C18.7065 10.0645 18.9677 10.3258 18.9677 10.6452V13.3548C18.9677 13.6742 18.7065 13.9355 18.3871 13.9355H5.6129Z"
                                      fill="#8D1CBA"
                                      />
                                  </svg>
                              </button>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            </CloneComponent>
            <div class="flex justify-end mt-6">
              <Link :href="route('modules.index')">
                <button
                    type="button"
                    class="inline-block px-6 py-3 m-0 font-bold text-center uppercase align-middle transition-all bg-gray-200 border-0 rounded-lg cursor-pointer hover:scale-102 active:opacity-85 hover:shadow-soft-xs leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 text-slate-800"
                >
                  Cancel
                </button>
              </Link>
              <PrimaryButton
                  btnClass="btn-primary px-4 transition ease-in-out duration-150 ms-3"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
              >
                Create Module
              </PrimaryButton>
            </div>

          </template>
        </FormSectionCustom>
    </Container>
  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Container from "@/Components/Container.vue";
import FormSectionCustom from "@/Components/FormSectionCustom.vue";
import CloneComponent from '@/Components/CloneComponent.vue';
import { ref } from "vue";
import { notify } from "notiwind";

const form = useForm({
    _method: 'POST',
    name: '',
    permissions: [],
});

const initialInputData = ref([{ name: '' }]);

const updateInputField = (index, updatedInput) => {
    initialInputData.value[index] = updatedInput;
    form.permissions = initialInputData.value;
};

const cloneInputData = (value) => {
    initialInputData.value = value;
    form.permissions[initialInputData.value.length - 1] = { name: '' };
}

const createNewModule = async () => {
    axios.post(route('modules.store'), form)
    .then(response => {
        router.visit(route('modules.index', { 'entries':10, 'page':1 }), {
                onSuccess: () => {
                    notify({
                        group: "success",
                        title: "Success",
                        text: "Module successfully created!"
                    }, 4000)
                }
            })
        })
    .catch(error => {
        form.processing = false;
        if (error.response && error.response.data && error.response.data.errors) {
          form.errors = error.response.data.errors;
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

</script>

<style>
.div-margin{
    margin-top:20px;
}
</style>