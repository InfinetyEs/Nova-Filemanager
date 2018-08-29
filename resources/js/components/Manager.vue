<template>
    <div class=" p-3 ">
        <nav class="bg-grey-light rounded font-sans w-full m-4">
            <ol class="list-reset flex text-grey-dark">
                <li><span class="text-blue font-bold cursor-pointer" @click="goToFolderNav('/')">{{ __('Home') }}</span></li>
                <li v-if="pathsLength > 0"><span class="mx-2">/</span></li>
            

                <template v-for="(folder ,index) in path">
                    <template v-if="checkIsLastItem(index)">
                        <li  v-bind:key="index"><span href="#" class="text-blue">{{ folder.name }}</span></li>
                    </template>
                    <template v-else>
                        <li  v-bind:key="index"><span href="#" class="text-blue cursor-pointer font-bold" @click="goToFolder(folder.path)">{{ folder.name }}</span></li>
                        <li  v-bind:key="index+'_separator'"><span class="mx-2">/</span></li>
                    </template>
                
                <!-- <li v-if="checkIsLastItem(index)"><span class="mx-2">/</span></li> -->
                </template>
            </ol>
        </nav>
        <transition name="fade">
            <div class="px-2 files" v-cloak>
                <div class="flex flex-wrap -mx-2">

                    <template v-if="files.error">
                        <div class="w-full text-lg text-center my-4">
                            {{ __('You don\'t have permissions to view this folder') }}
                        </div>
                    </template> 

                    <template v-if="noFiles">
                        <div class="w-full text-lg text-center my-4">
                            {{ __('No files or folders in current directory') }}<br><br>
                            <button class="btn btn-default btn-danger" @click="removeDirectory">{{ __('Remove directory') }}</button>
                        </div>
                    </template>

                    <template v-if="!files.error" v-for="file in files">
                        <div class="w-1/6 h-40  px-2 mb-3" v-bind:key="file.id">
                            <template v-if="file.type == 'file'">
                                <ImageLoader :file="file" class="h-40" @missing="(value) => missing = value" v-on:refresh="refresh" />
                            </template>
                            <template v-if="file.type == 'dir'">
                                <Folder :file="file" class="h-40"  v-on:goToFolderEvent="goToFolder" />
                            </template>
                        </div>
                    </template>


                </div>
                 

            </div>
        </transition>
    </div>
</template>

<script>
    import _ from 'lodash'
    import api from '../api';
    import ImageLoader from '../modules/ImageLoader'
    import Folder from '../modules/Folder'

    export default {

        components: {
            'ImageLoader': ImageLoader,
            'Folder': Folder,
        },

    	props: {
    		files: {
                default: function () {
                    return []
                },
                required: true
            }, 
            path : {
                default: function () {
                    return []
                },
                required: true
            },
            current : {
                type: String,
                default: '/',
                required: true
            },
            noFiles : {
                type: Boolean,
                default: false,
                required: true
            }
    	},

        data: () =>  ({
            loading: true,
        }),

        methods: {
            goToFolder(path) {
                this.$emit('goToFolderManager', path)
            },

            goToFolderNav(path) {
                this.$emit('goToFolderManagerNav', path)
            },

            checkIsLastItem(index){ 
                return (_.size(this.path) == (parseInt(index) + 1)) ? true : false;
            },

            removeDirectory()
            {
                return api.removeDirectory(this.current).then(result => {
                    if (result == true) {
                        this.$toasted.show(this.__('Folder removed successfully'), { type: 'success' })
                        this.$emit('goToFolderManager', '/');
                    } else {
                        this.$toasted.show(this.__('Error removing the folder. Please check permissions'), { type: 'error' })
                    }
                    
                });
            },

            refresh() {
                this.$emit('refresh');
            }
        },

        computed: {
            pathsLength() {
                return _.size(this.path);
            },

            filesCount(){
                return _.size(this.files);
            }
        }
    }
</script>

<style>
    /* Scoped Styles */
    .w-1\/8 {
        width: 12.5%;
    }
    .w-40 {
        width: 10rem;
    }

    .h-40 {
        height: 10rem;
    }

    .obfit-cover {
        object-fit: cover;
    }
</style>
