<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    dataObject: Object
});
</script>

<template>
        <div class="dataTable-bottom">
            <nav class="flex">
                <div class="flex justify-start items-center my-5 text-sm">
                    {{ "Showing " + dataObject.from + " to " + dataObject.to + " of " + dataObject.total + " entries" }}
                </div>
            </nav>
            <div v-if="dataObject.links.length > 3">
            <nav class="flex justify-end">
                <div class="flex flex-wrap mt-4 mb-2 items-center">
                    <template v-for="(link, key) in dataObject.links" :key="key">
                        <div
                            v-if="link.url === null"
                            class="mx-1 flex h-9 w-9 items-center justify-center rounded-full border border-gray-400 bg-transparent p-0 text-sm text-gray-800 transition duration-150 ease-in-out font-semibold"
                            v-html="key == 0 ? '«' : key == (dataObject.links.length - 1)  ? '»' : link.label"
                        />

                        <Link
                            v-else
                            class="mx-1 flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 bg-transparent shadow-sm p-0 text-sm text-gray-800 font-semibold transition duration-150 ease-in-out hover:bg-indigo-500 hover:border-primary hover:text-white"
                            :class="{ '!bg-indigo-500 text-white border-primary': link.active }"
                            :href="link.url"
                            v-html="key == 0 ? '«' : key == (dataObject.links.length - 1)  ? '»' : link.label"
                        />
                    </template>
                </div>
            </nav>
        </div>
        </div>
</template>

<style scoped>
.dataTable-top > nav:first-child, .dataTable-top > div:first-child, .dataTable-bottom > nav:first-child, .dataTable-bottom > div:first-child {
  float: left !important;
}
</style>
