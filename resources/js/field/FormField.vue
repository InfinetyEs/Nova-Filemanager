<template>
    <default-field :field="field">
        <template slot="field">

            <modal-filemanager 
                ref="filemanager"
                :active="openModal"
                :currentPath="currentPath"
                v-on:open-modal="openModalCreateFolder" 
                v-on:close-modal="closeFilemanagerModal" 
                v-on:update-current-path="updateCurrentPath"
                v-on:showInfoItem="showInfoItem"
                v-on:uploadFiles="uploadFiles"
                :value="value">
                    
            </modal-filemanager>

            <DetailPopup 
                ref="detailPopup"
                :info="info"
                :active="activeInfo"
                :popup="'true'"
                v-on:closePreview="closePreview" 
                v-on:refresh="refreshCurrent"
                v-on:selectFile="setValue"
            >
            </DetailPopup>


            <create-folder ref="createFolderModal" :active="showCreateFolder" :current="currentPath" v-on:closeCreateFolderModal="closeModalCreateFolder" v-on:refresh="refreshCurrent" />

            <UploadProgress ref="uploader" :current="currentPath" v-on:removeFile="removeFileFromUpload"></UploadProgress>

            <file-select :id="field.name" :field="field" :css="errorClasses"  v-model="value" v-on:open-modal="openFilemanagerModal"></file-select>

            <p class="mt-3 flex items-center text-sm" v-if="value">
                <button type="button" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center" @click="openRemoveModal">
                    <icon type="delete" view-box="0 0 20 20" width="16" height="16" />
                    <span class="class ml-2 mt-1">
                        {{__('Delete')}}
                    </span>
                </button>
            </p>


            <portal to="modals">
                <transition name="fade">
                    <confirm-modal-remove-file
                        v-if="removeModalOpen"
                        @confirm="removeFile"
                        @close="closeRemoveModal"
                    />
                </transition>
            </portal>

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
import CreateFolderModal from '../components/CreateFolderModal';
import DetailPopup from '../components/DetailPopup';
import UploadProgress from '../components/UploadProgress';
import ConfirmModalRemoveFile from '../components/ConfirmModalRemoveFile'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    components: {
        'file-select': FileSelect,
        'modal-filemanager': ModalFileManager,
        'create-folder': CreateFolderModal,
        DetailPopup: DetailPopup,
        UploadProgress: UploadProgress,
        'confirm-modal-remove-file': ConfirmModalRemoveFile,
    },

    data: () => ({
        openModal: false,
        showCreateFolder: false,
        currentPath: '/',

        //modalFile
        info: {},
        activeInfo: false,
        popupDetailsLoaded: false,

        //uploader
        filesToUpload: {},

        removeModalOpen: false
    }),

    methods: {
        openModalCreateFolder() {
            this.showCreateFolder = true;
        },
        closeModalCreateFolder() {
            this.showCreateFolder = false;
        },

        refreshCurrent() {
            this.$refs.filemanager.getData(this.currentPath);
        },

        openFilemanagerModal() {
            this.setCurrentPath();
            this.openModal = true;
        },

        closeFilemanagerModal() {
            this.openModal = false;
        },

        updateCurrentPath(val) {
            this.currentPath = val;
        },

        showInfoItem(item) {
            this.activeInfo = true;
            this.info = item;
        },

        closePreview() {
            this.info = {};
            this.activeInfo = false;
            this.popupDetailsLoaded = false;
        },

        uploadFiles(files) {
            this.filesToUpload = files;
            this.$refs.uploader.startUploadingFiles(files);
        },

        removeFileFromUpload(uploadedFileId) {
            let index = this.filesToUpload.map(item => item.id).indexOf(uploadedFileId);

            this.$delete(this.filesToUpload, index);
            if (this.filesToUpload.length === 0) {
                this.refreshCurrent();
            }
        },

        setCurrentPath() {
            if (this.field.folder != null) {
                this.currentPath = this.field.folder;
            } else {
                this.currentPath = '/';
            }
        },

        removeFile() {
            this.value = null;
            this.removeModalOpen = false;
        },

        openRemoveModal() {
            this.removeModalOpen = true;
        },

        closeRemoveModal() {
            this.removeModalOpen = false;
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
            this.closeFilemanagerModal();
        },
    },

    created() {
        this.setCurrentPath();
    },
};
</script>
