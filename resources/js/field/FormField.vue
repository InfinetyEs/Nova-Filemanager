<template>
    <default-field :field="field">
        <template slot="field">

            <modal-filemanager ref="managercontainer" :active="openModal" v-on:close-modal="closeFilemanagerModal" v-on:setFileValue="setValue" :value="value"></modal-filemanager>

            <file-select :id="field.name" :field="field" :css="errorClasses"  v-model="value" v-on:open-modal="openFilemanagerModal"></file-select>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import FileSelect from './custom/FileSelect';
import ModalFileManager from '../components/ModalFileManager';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        'file-select': FileSelect,
        'modal-filemanager': ModalFileManager,
    },

    data: () => ({
        openModal: false,
    }),

    methods: {
        openFilemanagerModal() {
            this.openModal = true;
        },

        closeFilemanagerModal() {
            this.openModal = false;
        },

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || '';
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '');
        },

        /**
         * Update the field's internal value.
         */
        setValue(file) {
            this.value = file.path;
        },
    },
};
</script>
