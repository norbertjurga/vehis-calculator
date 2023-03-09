<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onBeforeMount, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import Radio from '@/Components/Radio.vue';
import InputError from '@/Components/InputError.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import axios from 'axios';


const datepicker = ref(null);
const showInstalmentOptions = ref(false);
const errors = ref([]);

const form = ref(null);
const calculation = ref(null);

const openYearMenu = () => {
    if (datepicker) datepicker.value.openMenu()
}

const setBaseValues = () => {
    form.value = {
        year_of_production: new Date().getFullYear(),
        car_value: 0,
        is_price_net: true,
        with_gps: true,
        payments_no: 1,
    };
}

onBeforeMount(() => {
    setBaseValues();
});

// watch showInstalmentOptions
watch(showInstalmentOptions, (value) => {
    if (!value) {
        form.value.payments_no = 1;
    }

    form.value.payments_no = 2;

});

const reset = () => {
    console.log('reset');
    setBaseValues();
    showInstalmentOptions.value = false;
    errors.value = null;
    calculation.value = null;
}

const submitCalculation = () => {
    axios.post(route('calculate'), form.value).then(response => {
        errors.value = null;
        calculation.value = response.data.data;
    }).catch(error => {
        errors.value = error.response.data.errors;
    });
}

</script>

<template>
    <Head title="Kalkulator Vehis" />
    <div class="w-full min-h-screen py-12 px-4 md:px-0 flex items-center justify-center">
        <div class="w-full max-w-2xl">
            <img src="../../img/image.png" class="mx-auto w-24 rounded-lg shadow-lg">
            <template v-if="!calculation">
                <form @submit.prevent="submitCalculation">
                    <div
                        class="w-full bg-gradient-to-r from-base-200 to-base-300 rounded-lg shadow-lg mt-10 p-12 space-y-6">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Rocznik samochodu</span>
                            </label>

                            <input @click.prevent="openYearMenu" type="text" v-model="form.year_of_production"
                                class="input input-bordered w-full" />

                            <VueDatePicker v-model="form.year_of_production" ref="datepicker" auto-apply
                                :max-date="new Date()" year-picker dark />

                            <InputError class="mt-2" v-if="errors?.year_of_production"
                                :message="errors?.year_of_production[0]" />
                        </div>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Wartość samochodu</span>
                            </label>
                            <label class="input-group justify-between items-stretch jus w-full">
                                <input type="number" v-model="form.car_value" placeholder="0"
                                    class="input w-full input-bordered" />
                                <span class="bg-base-200">PLN</span>
                            </label>
                            <InputError class="mt-2" v-if="errors?.car_value" :message="errors.car_value[0]" />
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Cena netto</span>
                                <Checkbox v-model:checked="form.is_price_net" />
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Wyposażenie w GPS</span>
                                <Checkbox v-model:checked="form.with_gps" />
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <div>
                                    <p class="label-text">Rozłóż na raty</p>
                                    <p class="label-text text-xs">Za dodatkową opłatą 200PLN</p>
                                </div>
                                <Checkbox v-model:checked="showInstalmentOptions" />
                            </label>
                        </div>
                        <div class="p-8 bg-base-100 rounded-lg transition" v-show="showInstalmentOptions">
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <Radio :value="2" v-model:checked="form.payments_no" class="checked:bg-red-500" />
                                    <span class="label-text text-right">2 raty</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <Radio :value="4" v-model:checked="form.payments_no" class="checked:bg-red-500" />
                                    <span class="label-text text-right">4 raty</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-12 flex items-center justify-between">
                            <button @click.prevent="reset"
                                class="text-error uppercase transition hover:text-red-600 text-xs">Resetuj</button>
                            <button type="submit"
                                class="btn hover:scale-105 hover:shadow-lg border-0 text-gray-50 bg-gradient-to-br from-[#2f2757] to-[#b10000]">Dalej</button>
                        </div>
                    </div>
                </form>
            </template>
            <template v-else>
                <div class="w-full bg-gradient-to-r from-base-200 to-base-300 rounded-lg shadow-lg mt-10 p-12">

                    <div class="flex justify-between items-center">
                        <p class="text-2xl">Cena całkowita:</p>
                        <div class="px-4 py-2 rounded-lg bg-base-100 font-bold text-gray-50 text-3xl font-mono">{{
                            calculation.insurance_cost }} PLN</div>
                    </div>
                    <div class="divider mt-12"></div>
                    <div>
                        <p class="text-xl mb-2 mt-4">Wartość samochodu:</p>
                        <div>
                            <div class="font-bold text-xl font-mono">{{ calculation.price_net }} PLN netto</div>
                            <div class="text-xs m-0 font-mono">{{ calculation.price_gross }} PLN brutto</div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xl mt-4">Rok produkcji:</p>
                        <div>
                            <div class="font-bold text-xl font-mono">{{ calculation.year_of_production }}</div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <div class="flex">
                            <input type="checkbox" class="checkbox cursor-default checkbox-success"
                                @click.prevent="() => { }" :checked="calculation.with_gps" />
                            <div class="ml-4 font-bold text-xl font-mono">GPS</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex">
                            <input type="checkbox" class="checkbox cursor-default checkbox-success"
                                @click.prevent="() => { }" :checked="calculation.payments_no > 1" />
                            <div class="ml-4 font-bold text-xl font-mono">RATY</div>
                        </div>
                    </div>
                    <div class="p-8 mt-4 bg-base-100 rounded-lg transition"
                        v-show="calculation.payments_no > 1">
                        <div class="flex justify-between items-center">
                            <p>
                                Ilość rat:
                            </p>
                            <p class="font-mono text-xl">{{ calculation.payments_no }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p>
                                Wysokość pojedyńczej raty:
                            </p>
                            <p class="font-mono text-xl">{{ calculation.installment_cost }} PLN</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button @click.prevent="reset"
                            class="text-error uppercase transition hover:text-red-600 text-xs">Resetuj</button>
                    </div>

                </div>
            </template>
    </div>
</div></template>


