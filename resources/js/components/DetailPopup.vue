<template>
    <!-- Esto falla al hacer el mounted  -->
    <portal to="modals" name="File Details">
        <transition name="fade">
            <modal v-if="active">
                <div class="bg-white rounded-lg shadow-lg" style="width: 70vw;">
                    
                    <div class="bg-30 flex flex-wrap border-b border-70">
                        <div class="w-3/4 px-4 py-3 ">
                            {{ __('Preview of') }} <span class="text-primary-70%">{{ info.name }}</span>

                        </div>

                        <div class="w-1/4 flex flex-wrap justify-end">
                            <button class="btn buttons-actions" v-on:click="closePreview">X</button>
                        </div>
                    </div>

                    <div class="flex flex-wrap">
                        <div class="w-3/5 box-preview flex justify-center" :class="cssType">

                            <template v-if="info.type == 'image'">
                                <ImageInfo :file="info" />
                            </template>

                            <template v-else-if="info.type == 'audio'">
                                <audio ref="audio" controls>
                                    <source :src="info.src" :type="info.mime"/>
                                </audio>
                            </template>

                            <template v-else-if="info.type == 'video'">
                                <video ref="video" controls crossorigin playsinline>
                                    <source :src="info.url" :type="info.mime"/>
                                </video>
                            </template>


                            <template v-else-if="info.type == 'text'">
                                <codemirror v-if="codeLoaded" ref="code" :value="info.source" :options="cmOptions" >
                                </codemirror>
                            </template>

                            <template v-else-if="info.type == 'zip'">
                                <TreeView v-if="zipLoaded" :json="info.source" :name="info.name">
                                </TreeView>
                            </template>

                            <!-- <template v-else-if="info.type == 'word'">
                                <iframe :src="'https://view.officeapps.live.com/op/embed.aspx?src='+info.url" width="100%" height="100%" style="border: none;">
                                    <object class="no-preview" v-html="info.image"></object>
                                </iframe>
                            </template> -->

                            <template v-else-if="info.type == 'pdf'">
                                <object :data="info.url" type="application/pdf" width="100%" height="100%">
                                    <iframe :src="info.url" width="100%" height="100%" style="border: none;">
                                        <object class="no-preview" v-html="info.image">

                                        </object>
                                    </iframe>
                                </object>
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
                                            <button class="copy flex items-center leading-normal bg-50 rounded rounded-l-none border border-l-0 border-70 px-3 whitespace-no-wrap text-grey-dark text-xs" v-copy="info.url" v-copy:callback="onCopy">{{ __('Copy') }}</button>
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
import TreeView from './TreeView';
import { copy } from 'v-copy';
import Plyr from 'plyr';
import 'plyr/dist/plyr.css';
import { codemirror } from 'vue-codemirror';

//themes
import 'codemirror/lib/codemirror.css';
import 'codemirror/theme/dracula.css';

//modes
import 'codemirror/mode/markdown/markdown';
import 'codemirror/mode/javascript/javascript';
import 'codemirror/mode/php/php';
import 'codemirror/mode/sql/sql';
import 'codemirror/mode/ruby/ruby';
import 'codemirror/mode/shell/shell';
import 'codemirror/mode/sass/sass';
import 'codemirror/mode/yaml/yaml';
import 'codemirror/mode/yaml-frontmatter/yaml-frontmatter';
import 'codemirror/mode/nginx/nginx';
import 'codemirror/mode/xml/xml';
import 'codemirror/mode/vue/vue';
import 'codemirror/mode/dockerfile/dockerfile';
import 'codemirror/keymap/vim';

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
        codemirror: codemirror,
        TreeView: TreeView,
    },

    directives: {
        copy,
    },

    data: () => ({
        messagesRemove: ['Remove File', 'Are you sure', 'Removing...'],
        cssType: ' py-custom',
        codeLoaded: false,
        zipLoaded: false,
        cmOptions: {
            tabSize: 2,
            theme: 'dracula',
            lineNumbers: true,
            line: true,
        },
    }),

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

    mounted() {
        this.$nextTick(function() {
            this.messagesRemove = [
                this.__('Remove File'),
                this.__('Are you sure?'),
                this.__('Removing...'),
            ];
        });
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

    updated() {},

    watch: {
        'info.type': function(type) {
            if (type == 'audio') {
                this.$nextTick(function() {
                    setTimeout(() => {
                        this.cssType = ' py-custom items-center';
                        new Plyr(this.$refs.audio);
                    });
                });
            }

            if (type == 'video') {
                this.$nextTick(function() {
                    setTimeout(() => {
                        // this.cssType = 'items-center';
                        new Plyr(this.$refs.video);
                    });
                });
            }

            if (type == 'text') {
                this.$nextTick(function() {
                    this.cssType = '';
                    this.cmOptions.mode = this.info.mime;
                    this.codeLoaded = true;
                });
            }

            if (type == 'pdf') {
                this.$nextTick(function() {
                    this.cssType = 'mh400';
                });
            }

            if (type == 'zip') {
                this.$nextTick(function() {
                    this.info.source = JSON.parse(this.info.source);
                    this.zipLoaded = true;
                });
            }

            this.cssType = '';
            this.codeLoaded = false;
            this.zipLoaded = false;
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

.py-custom {
    padding: 2rem 0;
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

.vue-codemirror {
    width: 100%;
}

.mh400 {
    min-height: 400px;
}
</style>

<style>
.no-preview > .svg-mime {
    width: 150px;
    height: 100%;
}
.CodeMirror {
    height: 400px !important;
}
</style>
