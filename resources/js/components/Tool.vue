<template>
    <div v-if="loaded" ref="filemanager-container">
        <heading class="mb-6">FileManager</heading>

        <transition name="fade">
            <div class="w-full border-dashed border-grey border-50 mb-4" v-if="showUpload">
                <upload :current="currentPath" v-on:refresh="refreshCurrent"></upload>
            </div>
        </transition>

        <UploadProgress :current="currentPath" :files="filesToUpload"  v-on:removeFile="removeFileFromUpload"></UploadProgress>

        <create-folder :active="showCreateFolder" :current="currentPath" v-on:closeCreateFolderModal="closeModalCreateFolder" v-on:refresh="refreshCurrent" />

        <div class="card relative" id="filemanager-manager" :class="cssFilemenagerContainer">

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
import filesize from 'filesize';
import MD5 from '../tools/md5'
import api from '../api';
import CreateFolderModal from './CreateFolderModal';
import Manager from './Manager';
import Upload from './Upload';
import UploadProgress from './UploadProgress';

let arrayFiles = [];

export default {
    components: {
        upload: Upload,
        'create-folder': CreateFolderModal,
        manager: Manager,
        'UploadProgress': UploadProgress,
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
        cssDragAndDrop: null,
        eventsLoaded: false,
        filesToUpload: [],
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

    updated: function () {
        if (!this.eventsLoaded) {
            this.$nextTick(function () {
                this.eventsLoaded = true;
                this.setDragAndDropEvents();
            })    
        }        
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

        refreshCurrentAfterUpload() {
            this.getData(this.currentPath);
            this.filesToUpload = []
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

        setDragAndDropEvents() {
            console.log("timesss");
            let self = this;
            let filemanagerContainer = document.querySelector("#filemanager-manager");

            filemanagerContainer.addEventListener("dragenter", function (e) {
                e.preventDefault();
                self.cssDragAndDrop = "inside";
            });

            filemanagerContainer.addEventListener("dragleave", function (e) {
                e.preventDefault();
                self.cssDragAndDrop = "outside";
            });

            filemanagerContainer.addEventListener("dragover", function (e) {
                e.preventDefault();
                self.cssDragAndDrop = "inside";
            });

            filemanagerContainer.addEventListener("drop", function (e) {
                e.preventDefault();
                self.cssDragAndDrop = "drop";
              
                let files = e.dataTransfer.files;

                
                Array.from(files).forEach((file) => {
                    arrayFiles.push({
                        id: MD5(file.name),
                        preview: self.getPreview(file),
                        type: file.type,
                        name: file.name,
                        size: filesize(file.size),
                        upload: true,
                        progress: 0,
                        error: false,
                        file: file,
                    })
                })

                self.filesToUpload = arrayFiles;
                console.log("Drop files:", self.filesToUpload);
                //this.uploadFile(files);
            });
        },

        getPreview(file) {
            
            if (this.isImage(file)) {
                return  URL.createObjectURL(file);
            }

            return file.name.split('.').pop();;
        },

        isImage(file){
           return (file.type.includes('image'));//returns true or false
        },

        removeFileFromUpload(uploadedFileIndex) {
            this.$delete(this.filesToUpload, uploadedFileIndex)

            if (this.filesToUpload.length === 0) {
                this.refreshCurrent()
            }
        }
    },

    computed: {
        cssFilemenagerContainer() {
            console.log(this.cssDragAndDrop)
            if (this.cssDragAndDrop == 'inside') {
                return 'bg-20';
            }

            if (this.cssDragAndDrop == 'outside') {
                return '';
            }


            return '';
        }
    }
    

 
};
</script>

<style>
/* Scoped Styles */
</style>
