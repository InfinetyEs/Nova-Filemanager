<template>
    <portal to="modals" name="File Preview">
        <transition name="fade">
            <modal v-if="active">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 900px;">
                    
                    <div class="bg-30 flex flex-wrap border-b border-70">
                        <div class="w-3/4 px-4 py-3 ">
                            {{ __('Preview of') }} <span class="text-primary-70%">{{ info.name }}</span>

                        </div>

                        <div class="w-1/4 flex flex-wrap justify-end">
                            <button class="btn buttons-actions" v-on:click="closePreview">X</button>
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-3/5 py-view box-preview flex justify-center">

                            <template v-if="info.type == 'image'">
                                <ImageInfo :file="info" />
                            </template>

                            <template v-else-if="info.type == 'others'">
                                
                                <audio controls>
                                    <source :src="info.src" :type="info.mime"/>
                                </audio>
                                
                            </template>
                            
                            <template v-else>
                                <object class="no-preview" v-html="info.image">

                                </object>
                            </template>


                        </div>
                        <div class="w-2/5 bg-30 box-info flex flex-wrap">

                            <div class="info-data w-full">
                                <div class="info mx-4 my-3 flex flex-wrap">
                                    <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Name') }}:</span>
                                    <span class="value bg-white px-1 py-1 rounded-r">{{ info.name }}</span>
                                </div>

                                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.mime">
                                    <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Mime Type') }}:</span>
                                    <span class="value bg-white px-1 py-1 rounded-r">{{ info.mime }}</span>
                                </div>

                                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.date">
                                    <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Last Modification') }}:</span>
                                    <span class="value bg-white px-1 py-1 rounded-r">{{ info.date }}</span>
                                </div>

                                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.size">
                                    <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Size') }}:</span>
                                    <span class="value bg-white px-1 py-1 rounded-r">{{ info.size }}</span>
                                </div>

                                <div class="info mx-4 my-3 flex flex-wrap" v-if="info.dimensions">
                                    <span class="title bg-50 px-1 py-1 rounded-l">{{ __('Dimensions') }}:</span>
                                    <span class="value bg-white px-1 py-1 rounded-r">{{ info.dimensions }}</span>
                                </div>

                                <div class="info mx-4 mt-6" v-if="info.url">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-value">
                                        {{ __('Url') }}
                                    </label>

                                    <div class="flex flex-wrap items-stretch w-full mb-4 relative">
                                        <input type="text" class="flex-shrink flex-grow flex-auto text-xs leading-normal w-px flex-1 border border-70 rounded rounded-r-none px-1 relative" :value="info.url">
                                        <div class="flex -mr-px">
                                            <button class="copy flex items-center leading-normal bg-50 rounded rounded-l-none border border-l-0 border-70 px-3 whitespace-no-wrap text-grey-dark text-xs" v-copy="info.url" v-copy:callback="onCopy">Copy</button>
                                        </div>  
                                    </div>  
                                </div>
                            </div>

                            <div class="info-actions w-full flex flex-wrap self-end justify-end">
                                <!-- <button type="button" data-testid="cancel-button" @click.prevent="removeFilePopup" class="btn text-danger text-sm font-normal h-9 px-3 mr-3 btn-link">{{ __('Remove file') }}</button> -->
                                <div :class="{ 'm-3': popup }">
                                    <confirmation-button
                                        :messages="messagesRemove"
                                        :css="'btn text-danger text-sm font-normal h-9 px-3 mr-3 btn-link'"
                                        v-on:confirmation-success="removeFilePopup()"></confirmation-button>


                                    <template v-if="popup">
                                        <button @click="selectFile" class="btn btn-default btn-primary">
                                            {{ __('Select file') }}
                                        </button>
                                    </template>
                                </div>
                                
                            </div>

                        </div>

                    </div>

                    
                </div>
            </modal>
        </transition>
    </portal>
</template>

<script>
import api from '../api';
import ImageInfo from '../modules/Image';
import ConfirmationButton from './ConfirmationButton';
import { copy } from 'v-copy';

export default {
    props: {
        active: {
            type: Boolean,
            default: false,
            required: true,
        },
        info: {
            type: Object,
            default: function() {
                return { name: '' };
            },
            required: true,
        },
        popup: {
            type: Boolean,
            default: false,
            required: false,
        },
    },

    components: {
        ImageInfo: ImageInfo,
        ConfirmationButton: ConfirmationButton,
    },

    directives: {
        copy,
    },

    data: () => ({
        messagesRemove: ['Remove File', 'Are you sure', 'Removing...'],
    }),
    mounted() {
        //
    },

    methods: {
        closePreview() {
            this.$emit('closePreview', true);
        },

        onCopy() {
            this.$toasted.show(this.__('Text copied to clipboard'), { type: 'success' });
        },

        removeFilePopup() {
            this.closePreview();

            return api.removeFile(this.info.path).then(result => {
                if (result == true) {
                    this.$toasted.show(this.__('File removed successfully'), { type: 'success' });
                    this.$emit('refresh');
                } else {
                    this.$toasted.show(
                        this.__('Error removing the file. Please check permissions'),
                        { type: 'error' }
                    );
                }
            });
        },

        selectFile() {
            this.closePreview();
            this.$emit('selectFile', this.info);
        },
    },

    computed: {
        playerOptions() {
            if (this.info) {
                return {
                    video: {
                        url: this.info.name,
                    },
                    autoplay: false,
                    contextmenu: [
                        {
                            text: 'GitHub',
                            link: 'https://github.com/MoePlayer/vue-dplayer',
                        },
                    ],
                };
            }
            return {};
        },
    },
};
</script>

<style scoped lang="scss">
.buttons-actions {
    padding-left: 1rem;
    padding-right: 1rem;
    border-left: 1px solid rgb(221, 221, 221);
    // border-bottom: 1px solid rgb(221, 221, 221);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.box-preview {
    max-height: 500px;
}

.info {
    .title,
    .value {
        font-size: 0.75rem;
    }

    .value {
        //-moz-user-select: text;
        -khtml-user-select: text;
        -webkit-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }

    .copy {
        &:active,
        &:focus {
            outline: 0;
        }
    }
}
</style>

<style>
.no-preview > .svg-mime {
    width: 150px;
    height: 100%;
}
</style>
