<template>
    <div v-if="loaded">
        <heading class="mb-6">FileManager</heading>

        <transition name="fade">
            <div class="w-full border-dashed border-grey border-50 mb-4" v-if="showUpload">
                <upload :current="currentPath" v-on:refresh="refreshCurrent"></upload>
            </div>
        </transition>

        <create-folder :active="showCreateFolder" :current="currentPath" v-on:closeCreateFolderModal="closeModalCreateFolder" v-on:refresh="refreshCurrent" />

        <div class="card relative">

            <div class="p-3 flex items-center justify-between border-b border-50">
                <div class="flex flex-wrap">
                    <button @click="showUpload = !showUpload" class="btn btn-default btn-primary mr-3">
                        {{ __('Upload') }}
                    </button>

                    <button @click="showModalCreateFolder" class="btn btn-default btn-primary mr-3">
                        {{ __('Create folder') }}
                    </button>
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
                :files="files"
                :path="path"
                :current="currentPath"
                :noFiles="noFiles"
                :view="view"
                v-on:goToFolderManager="goToFolder"
                v-on:goToFolderManagerNav="goToFolderNav"
                v-on:refresh="refreshCurrent"
            />
            
        </div>
    </div>
</template>

<script>
import URI from 'urijs';
import _ from 'lodash';
import api from '../api';
import CreateFolderModal from './CreateFolderModal';
import Manager from './Manager';
import Upload from './Upload';

export default {
    components: {
        upload: Upload,
        'create-folder': CreateFolderModal,
        manager: Manager,
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
        view: 'grid',
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
            return api.getData(pathToList).then(result => {
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

        changeToList() {
            this.view = 'list';
            localStorage.setItem('view', 'list');
        },

        changeToGrid() {
            this.view = 'grid';
            localStorage.setItem('view', 'grid');
        },
    },

    events: {
        //
    },
};
</script>

<style>
/* Scoped Styles */
</style>
