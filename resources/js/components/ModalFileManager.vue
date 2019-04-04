<template>
    <portal to="modals" name="FileManager Field Modal">
        <transition name="fade">
            <modal v-if="active">

                <div class="bg-white rounded-lg shadow-lg" style="width: 900px;">
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


                            <div class="p-3  flex flex-wrap items-center border-b border-50">

                                <div class="w-1/3 flex flex-wrap justify-start">

                                    <label class="manual_upload cursor-pointer">
                                        <div @click="showUpload = !showUpload" class="btn btn-default btn-primary mr-3">
                                            {{ __('Upload') }}
                                        </div>
                                        <input type="file" multiple="true" @change="uploadFilesByButton"/>
                                    </label>

                                    <button @click="showModalCreateFolder" class="btn btn-default btn-primary mr-3">
                                        {{ __('Create folder') }}
                                    </button>

                                </div>

                                <!-- Search -->
                                <div class="w-2/3 flex flex-wrap justify-end">

                                    <div class="relative w-1/3 max-w-xs mr-3">
                                        <div class="relative">
                                            <div class="relative">

                                                <template v-if="showFilters">
                                                    <select class="pl-search form-control form-input form-input-bordered w-full" v-model="filterBy" @change="filterFiles">
                                                        <option value="">{{ __('Filter by ...') }}</option>
                                                        <option v-for="(filter, key) in filters" :key="'filter_' + key" :value="key">{{ key }}</option>
                                                    </select>    
                                                </template>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative w-1/2 max-w-xs">
                                        <div class="relative">
                                            <div class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                                                <input v-on:input="searchItems" v-model="search" dusk="filemanager-search" type="search" :placeholder="this.__('Search')" class="pl-search form-control form-input form-input-bordered w-full">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            
				            
                            <manager 
                                ref="manager"
                                :home="home"
                                :files="files"
                                :path="path"
                                :current="currentPath"
                                :noFiles="noFiles"
                                :selector="value"
                                :popupLoaded="true"
                                :loading="loadingfiles"
                                :search="search"
                                :filters="filteredExtensions"
                                v-on:goToFolderManager="goToFolder"
                                v-on:goToFolderManagerNav="goToFolderNav"
                                v-on:refresh="refreshCurrent"
                                v-on:selectFile="setFileValue"
                                v-on:showInfoItem="showInfoItem"
                                v-on:uploadFiles="uploadFiles"
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
import Manager from './Manager';
import Upload from './Upload';
import UploadProgress from './UploadProgress';

export default {
    props: {
        resource: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        active: {
            type: Boolean,
            default: false,
            required: true,
        },
        value: {
            type: String,
            required: true,
        },
        currentPath: {
            type: String,
            required: true,
        },
        home: {
            type: String,
            required: false,
            default: '/',
        },
        filter: {
            type: String,
            required: false,
            default: '',
        },
    },

    components: {
        upload: Upload,
        manager: Manager,
        UploadProgress: UploadProgress,
    },

    data: () => ({
        loaded: false,
        loadingfiles: false,
        activeDisk: null,
        activeDiskBackups: [],
        backupStatusses: [],
        showUpload: false,
        showCreateFolder: false,
        currentPathFolder: this.currentPath,
        files: [],
        path: [],
        noFiles: false,
        filesToUpload: [],
        firstTime: true,
        search: '',
        filters: [],
        filterBy: '',
        filteredExtensions: [],
        showFilters: false,
    }),

    methods: {
        getData(pathToList) {
            this.files = [];
            this.path = [];
            this.noFiles = false;
            this.loadingfiles = true;
            api.getDataField(this.resource, this.name, pathToList)
                .then(result => {
                    if (_.size(result.files) == 0) {
                        this.noFiles = true;
                    }
                    this.files = result.files;
                    this.path = result.path;
                    this.filters = result.filters;
                    this.loadingfiles = false;
                })
                .catch(() => {
                    this.loadingfiles = false;
                    this.filters = [];
                    this.$toasted.show(
                        this.__('Error reading the folder. Please check your logs'),
                        { type: 'error' }
                    );
                });
        },

        showModalCreateFolder() {
            this.$emit('open-modal');
        },

        refreshCurrent() {
            this.getData(this.currentPathFolder);
        },

        goToFolder(path) {
            // this.currentPath = this.currentPath + '/' + path;
            this.getData(path);
            this.currentPathFolder = path;
            // history.pushState(null, null, '?path=' + path);
        },

        goToFolderNav(path) {
            this.getData(path);
            this.currentPathFolder = path;
            if (this.currentPathFolder == '/') {
                // history.pushState(null, null, '?path=' + path);
            } else {
                // history.pushState(null, null, '?path=' + path);
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

        showInfoItem(item) {
            this.$emit('showInfoItem', item);
        },

        uploadFiles(files) {
            this.$emit('uploadFiles', files);
        },

        filterFiles() {
            let extensions = _.get(this.filters, this.filterBy);

            if (extensions == null) {
                this.filteredExtensions = [];
            }

            if (extensions != null && extensions.length > 0) {
                this.filteredExtensions = extensions;
            }
        },

        searchItems: _.debounce(function(e) {
            this.search = e.target.value;
        }, 300),
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
        currentPathFolder: function(val) {
            this.$emit('update-current-path', val);
        },

        filters() {
            if (this.filters) {
                let size = _.size(this.filters);
                if (size > 1) {
                    this.showFilters = true;
                    return true;
                }
            }

            this.showFilters = false;
        },
    },
};
</script>

<style scoped>
.buttons-actions {
    padding-left: 1rem;
    padding-right: 1rem;
    border-left: 1px solid rgb(221, 221, 221);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.manual_upload > input[type='file'] {
    display: none;
}
</style>
