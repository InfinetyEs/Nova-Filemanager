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

            <div class="p-3 flex items-center border-b border-50">
                <button @click="showUpload = !showUpload" class="btn btn-default btn-primary mr-3">
                    {{ __('Upload') }}
                </button>

                <button @click="showModalCreateFolder" class="btn btn-default btn-primary mr-3">
                    {{ __('Create folder') }}
                </button>
            </div>
            
            <manager 
                :files="files"
                :path="path"
                :current="currentPath"
                :noFiles="noFiles"
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
    },

    events: {
        //
    },
};
</script>

<style>
/* Scoped Styles */
</style>
