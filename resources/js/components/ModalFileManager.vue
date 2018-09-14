<template>
    <portal to="modals">
        <transition name="fade">
            <modal v-if="active">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 900px;">
                    <div class="bg-30 flex flex-wrap border-b border-70">
                        <div class="w-3/4 px-4 py-3 ">
                            {{ __('FileManager') }}

                        </div>

                        <div class="w-1/4 flex flex-wrap justify-end">
                            <button class="btn buttons-actions" v-on:click="closeModal">X</button>
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="card relative w-full">


                            <div class="p-3 flex items-center border-b border-50">
                                <label class="manual_upload cursor-pointer">
                                    <div @click="showUpload = !showUpload" class="btn btn-default btn-primary mr-3">
                                        {{ __('Upload') }}
                                    </div>
                                    <input type="file" multiple="true" @change="uploadFilesByButton"/>
                                </label>

                            </div>

                            
				            
                            <manager 
                                ref="manager"
                                :files="files"
                                :path="path"
                                :current="currentPath"
                                :noFiles="noFiles"
                                :selector="value"
                                :popupLoaded="true"
                                v-on:goToFolderManager="goToFolder"
                                v-on:goToFolderManagerNav="goToFolderNav"
                                v-on:refresh="refreshCurrent"
                                v-on:selectFile="setFileValue"
                            />
				            
                        </div>
                    </div>
                </div>
            </modal>
        </transition>
    </portal>
</template>

<script>
import _ from 'lodash';
import URI from 'urijs';
import api from '../api';
import CreateFolderModal from './CreateFolderModal';
import Manager from './Manager';
import Upload from './Upload';
import UploadProgress from './UploadProgress';

export default {
    props: {
        active: {
            type: Boolean,
            default: false,
            required: true,
        },
        value: {
            type: String,
            required: true,
        },
    },

    components: {
        upload: Upload,
        'create-folder': CreateFolderModal,
        manager: Manager,
        UploadProgress: UploadProgress,
    },

    data: () => ({
        loaded: false,
        activeDisk: null,
        activeDiskBackups: [],
        backupStatusses: [],
        showUpload: false,
        showCreateFolder: false,
        currentPath: '/',
        files: [],
        path: [],
        noFiles: false,
        filesToUpload: [],
        firstTime: true,
    }),

    methods: {
        getData(pathToList) {
            this.files = [];
            this.path = [];
            this.noFiles = false;
            api.getData(pathToList).then(result => {
                if (_.size(result.files) == 0) {
                    this.noFiles = true;
                }
                this.files = result.files;
                this.path = result.path;
            });
        },

        showModalCreateFolder() {
            this.showCreateFolder = true;
        },
        closeModalCreateFolder() {
            this.showCreateFolder = false;
        },

        refreshCurrent() {
            this.getData(this.currentPath);
        },

        goToFolder(path) {
            // this.currentPath = this.currentPath + '/' + path;
            this.getData(path);
            this.currentPath = path;
            history.pushState(null, null, '?path=' + path);
        },

        goToFolderNav(path) {
            this.getData(path);
            this.currentPath = path;
            if (this.currentPath == '/') {
                history.pushState(null, null, '?path=' + path);
            } else {
                history.pushState(null, null, '?path=' + path);
            }
        },

        closeModal() {
            this.$emit('close-modal');
        },

        setFileValue(file) {
            this.closeModal();
            this.$emit('setFileValue', file);
        },

        uploadFilesByButton(e) {
            this.$refs.manager.uploadFiles(e.target.files);
        },
    },
    watch: {
        active: function(val) {
            if (val) {
                let currentUrl = new URI();
                if (currentUrl.hasQuery('path')) {
                    let params = currentUrl.query(true);
                    this.currentPath = params.path;
                }

                this.getData(this.currentPath);
            }
        },
    },
};
</script>

<style scoped>
.buttons-actions {
    padding-left: 1rem;
    padding-right: 1rem;
    border-left: 1px solid rgb(221, 221, 221);
    // border-bottom: 1px solid rgb(221, 221, 221);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.manual_upload > input[type='file'] {
    display: none;
}
</style>
