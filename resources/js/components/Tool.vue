<template>
    <div  ref="filemanager-container">
        <heading class="mb-6">{{ __('Filemanager') }}</heading>

        <create-folder :active="showCreateFolder" :current="currentPath" v-on:closeCreateFolderModal="closeModalCreateFolder" v-on:refresh="refreshCurrent" />

        <div class="card relative" id="filemanager-manager">

            <div class="p-3 flex items-center justify-between border-b border-50">
                <div class="w-full flex flex-wrap">
                    <div class="w-1/2 flex flex-wrap justify-start">
                        <button @click="refreshCurrent" class="btn btn-default btn-small btn-primary text-white mr-3" :class="{'rotate': loadingfiles}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/></svg>
                        </button>

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
                    <div class="w-1/2 flex flex-wrap justify-end">

                        <div class="relative z-50  w-1/3 max-w-xs mr-3">
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

                        <div class="relative z-50 w-2/3 max-w-xs">
                            <div class="relative">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="search" role="presentation" class="fill-current absolute search-icon-center ml-3 text-70"><path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path></svg>
                                    <input v-on:input="searchItems" v-model="search" dusk="filemanager-search" type="search" :placeholder="this.__('Search')" class="pl-search form-control form-input form-input-bordered w-full">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">


                    <!-- <button @click="changeToList" class="btn btn-link mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" height="24" width="24">
                          <circle cx="24.879" cy="64" r="10.059" fill="#2f3435"/>
                          <circle cx="24.878" cy="100.09" r="10.059" fill="#2f3435"/>
                          <circle cx="24.879" cy="27.91" r="10.058" fill="#2f3435"/>
                          <path fill="none" stroke="#2f3435" stroke-miterlimit="10" stroke-width="14.334" d="M48.682 27.91h64.501M48.682 64h64.501M48.682 100.09h64.501"/>
                        </svg>
                    </button>

                    <button @click="changeToGrid" class="btn btn-link mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5 3h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 2v4h4V5h-4zM5 13h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4H5zm10-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4c0-1.1.9-2 2-2zm0 2v4h4v-4h-4z"/></svg>
                    </button> -->

                </div>
                
            </div>
            <manager
                ref="manager"
                :files="files"
                :path="path"
                :current="currentPath"
                :noFiles="noFiles"
                :view="view"
                :loading="loadingfiles"
                :search="search"
                :filters="filteredExtensions"
                v-on:goToFolderManager="goToFolder"
                v-on:goToFolderManagerNav="goToFolderNav"
                v-on:refresh="refreshCurrent"
                v-on:uploadFiles="uploadFiles"
                v-on:showInfoItem="showInfoItem"
            />

            <DetailPopup 
                ref="detailPopup"
                :info="info"
                :active="activeInfo"
                v-on:closePreview="closePreview" 
                v-on:refresh="refreshCurrent"
            >
            </DetailPopup>

            <UploadProgress ref="uploader" :current="currentPath" v-on:removeFile="removeFileFromUpload"></UploadProgress>
            
        </div>
    </div>
</template>

<script>
import URI from 'urijs';
import _ from 'lodash';
import api from '../api';
import CreateFolderModal from './CreateFolderModal';
import DetailPopup from './DetailPopup';
import UploadProgress from './UploadProgress';
import Manager from './Manager';

export default {
    components: {
        'create-folder': CreateFolderModal,
        manager: Manager,
        DetailPopup: DetailPopup,
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
        currentPath: '/',
        files: [],
        path: [],
        noFiles: false,
        view: 'grid',
        info: {},
        filesToUpload: [],
        activeInfo: false,
        search: '',
        filters: [],
        filterBy: '',
        filteredExtensions: [],
        showFilters: false,
    }),

    async created() {
        let currentUrl = new URI();

        if (currentUrl.hasQuery('path')) {
            let params = currentUrl.query(true);
            this.currentPath = params.path;
        }

        await this.getData(this.currentPath);

        this.loaded = true;
    },

    methods: {
        getData(pathToList) {
            this.files = [];
            this.path = [];
            this.noFiles = false;
            this.loadingfiles = true;
            return api
                .getData(pathToList)
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
            this.showCreateFolder = true;
        },
        closeModalCreateFolder() {
            this.showCreateFolder = false;
        },

        refreshCurrent() {
            this.getData(this.currentPath);
        },

        refreshCurrentAfterUpload() {
            this.getData(this.currentPath);
            this.filesToUpload = [];
        },

        goToFolder(path) {
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

        changeToList() {
            this.view = 'list';
            localStorage.setItem('view', 'list');
        },

        changeToGrid() {
            this.view = 'grid';
            localStorage.setItem('view', 'grid');
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

        uploadFilesByButton(e) {
            this.$refs.manager.uploadFiles(e.target.files);
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

    computed: {
        cssFilemenagerContainer() {
            if (this.cssDragAndDrop == 'inside') {
                return 'bg-20';
            }

            if (this.cssDragAndDrop == 'outside') {
                return '';
            }
            return '';
        },
    },

    watch: {
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

<style scoped lang="scss">
.manual_upload > input[type='file'] {
    display: none;
}

.btn-small {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-top: 0.3rem;
    fill: #fff;
}

.rotate {
    svg {
        animation: fa-spin 2s infinite linear;
    }
}

@keyframes fa-spin {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }
}
</style>
