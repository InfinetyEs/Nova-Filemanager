<template>

    <panel-item  v-if="display == 'normal'" :field="field" />

    <!-- <file-field v-else :field="field" /> -->

    <ImagePanel v-else :field="field" />
    
</template>

<script>
import ImagePanel from './custom/ImagePanel';

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    components: {
        ImagePanel: ImagePanel,
    },
    data: () => ({
        display: 'normal',
    }),
    mounted() {
        this.display = this.field.display || 'normal';
    },
};
</script>
