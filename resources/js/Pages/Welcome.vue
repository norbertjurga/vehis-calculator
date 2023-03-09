<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onBeforeMount, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import CalculationResultCard from '@/Components/CalculationResultCard.vue';
import CheckboxWrapper from '@/Components/CheckboxWrapper.vue';
import Radio from '@/Components/Radio.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/InputLabel.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import axios from 'axios';


const showInstalmentOptions  = ref(false);
const calculation            = ref(null);
const datepicker             = ref(null);
const errors                 = ref(null);
const form                   = ref(null);

const setBaseValues = () => {
    form.value = {
        year_of_production: new Date().getFullYear(),
        car_value: 0,
        is_price_net: true,
        with_gps: true,
        number_of_payments: 1,
    };
}

onBeforeMount(() => {
    setBaseValues();
});

const openYearMenu = () => {
    if (datepicker) datepicker.value.openMenu();
}

watch(showInstalmentOptions, (value) => {
    form.value.number_of_payments = value ? 2 : 1;
});

const reset = () => {
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

            <CalculationResultCard v-if="calculation" :calculation="calculation" @reset="reset" />

            <template v-else>
                <form @submit.prevent="submitCalculation">
                    <div
                        class="w-full bg-gradient-to-r from-base-200 to-base-300 rounded-lg shadow-lg mt-10 p-12 space-y-6">
                        <div class="form-control w-full">
                            <Label value="Rok produkcji" />

                            <input @click.prevent="openYearMenu" type="text" v-model="form.year_of_production"
                                class="input input-bordered w-full" />

                            <VueDatePicker v-model="form.year_of_production" ref="datepicker" auto-apply
                                :max-date="new Date()" year-picker dark />

                            <InputError class="mt-2" v-if="errors?.year_of_production"
                                :message="errors?.year_of_production[0]" />
                        </div>
                        <div class="form-control w-full">
                            <Label value="Wartość samochodu" />
                            <label class="input-group justify-between items-stretch jus w-full">
                                <input type="number" v-model="form.car_value" placeholder="0"
                                    class="input w-full input-bordered" />
                                <span class="bg-base-200">PLN</span>
                            </label>
                            <InputError class="mt-2" v-if="errors?.car_value" :message="errors.car_value[0]" />
                        </div>

                        <CheckboxWrapper>
                            <span class="label-text">Cena netto</span>
                            <Checkbox v-model:checked="form.is_price_net" />
                        </CheckboxWrapper>

                        <CheckboxWrapper>
                            <span class="label-text">Wyposażenie w GPS</span>
                            <Checkbox v-model:checked="form.with_gps" />
                        </CheckboxWrapper>

                        <CheckboxWrapper>
                            <div>
                                <p class="label-text">Rozłóż na raty</p>
                                <p class="label-text text-xs">Za dodatkową opłatą 200PLN</p>
                            </div>
                            <Checkbox v-model:checked="showInstalmentOptions" />
                        </CheckboxWrapper>

                        <div class="p-8 bg-base-100 rounded-lg transition" v-show="showInstalmentOptions">

                            <CheckboxWrapper>
                                <Radio :value="2" v-model:checked="form.number_of_payments" class="checked:bg-red-500" />
                                <span class="label-text text-right">2 raty</span>
                            </CheckboxWrapper>
                            <CheckboxWrapper>
                                <Radio :value="4" v-model:checked="form.number_of_payments" class="checked:bg-red-500" />
                                <span class="label-text text-right">4 raty</span>
                            </CheckboxWrapper>

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
        </div>
    </div>
</template>