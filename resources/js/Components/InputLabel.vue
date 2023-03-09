<script setup>
defineProps(['value']);
</script>

<template>
    <label class="label">
        <span v-if="value" class="label-text">{{ value }}</span>
        <span v-else class="label-text">
            <slot />
        </span>
    </label>
</template>
