<script setup>

const props = defineProps({
    calculation: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['reset']);

const reset = () => {
    emit('reset');
}

</script>

<template>
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
                <input type="checkbox" class="checkbox cursor-default checkbox-success" @click.prevent="() => { }"
                    :checked="calculation.with_gps" />
                <div class="ml-4 font-bold text-xl font-mono">GPS</div>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex">
                <input type="checkbox" class="checkbox cursor-default checkbox-success" @click.prevent="() => { }"
                    :checked="calculation.number_of_payments > 1" />
                <div class="ml-4 font-bold text-xl font-mono">RATY</div>
            </div>
        </div>
        <div class="p-8 mt-4 bg-base-100 rounded-lg transition" v-show="calculation.number_of_payments > 1">
            <div class="flex justify-between items-center">
                <p>
                    Ilość rat:
                </p>
                <p class="font-mono text-xl">{{ calculation.number_of_payments }}</p>
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