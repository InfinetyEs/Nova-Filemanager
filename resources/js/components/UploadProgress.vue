<template>
    <div class="stack-uploads fixed pin-b bg-white shadow" v-if="files.length > 0">
        <div class="files p-4" v-for="(file, indexFiles) in files" v-bind:key="indexFiles">
            <transition name="fade" >
                <div class="flex flex-wrap w-full items-center"  v-bind:key="indexFiles" v-if="file.upload == true">
                    <div class="preview w-1/3">
                        <img class="rounded-full w-12 h-12" v-if="isImage(file)" :src="file.preview">
                        <div v-else class="rounded-full bg-50 w-12 h-12 flex justify-center items-center">
                            <div class="uppercase">
                                {{ file.preview }}
                            </div>
                        </div>
                    </div>
                    <div class="w-2/3 text-xs">
                        <template v-if="file.error">
                            <div class="text-danger">{{ __('Error on upload') }}</div>
                        </template>
                        <template v-else>
                            {{ file.name }} <small v-if="file.progress == 100" class="text-success uppercase">{{ __('Success') }}</small>
                            <progress-module :file="file"></progress-module>
                        </template>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import Progress from '../modules/Progress';
let token = document.head.querySelector('meta[name="csrf-token"]');

export default {
    props: {
        current: {
            type: String,
            default: '/',
            required: true,
        },
        visibility: {
            type: String,
            default: 'public',
        },
    },

    components: {
        'progress-module': Progress,
    },

    data: () => ({
        token: token.content,
        files: [],
        filesUploaded: [],
    }),

    methods: {
        isImage(file) {
            return file.type.includes('image'); //returns true or false
        },

        getRandomArbitrary(min, max) {
            return Math.random() * (max - min) + min;
        },

        startUploadingFiles(files) {
            this.files = files;
            Array.from(this.files).forEach(file => {
                this.startUpload(file);
            });
        },

        startUpload(file) {
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: progressEvent => {
                    file.progress = parseInt(
                        Math.round((progressEvent.loaded * 100) / progressEvent.total)
                    );
                },
            };

            let data = new FormData();
            data.append('file', file.file);
            data.append('current', this.current);
            data.append('visibility', this.visibility);

            window.axios
                .post('/nova-vendor/infinety-es/nova-filemanager/uploads/add', data, config)
                .then(response => {
                    if (response.data.success == true) {
                        _.forEach(this.files, fileUpload => {
                            if (fileUpload.name == response.data.name) {
                                fileUpload.upload = true;
                            }
                        });
                        this.filesUploaded.push(file.id);

                        setTimeout(() => {
                            this.$emit('removeFile', file.id);
                        }, 2000);
                    } else {
                        this.$toasted.show(
                            this.__(
                                'Error uploading the file. Check your MaxFilesize or permissions'
                            ),
                            { type: 'error' }
                        );
                    }
                })
                .catch(() => {
                    file.error = true;
                    this.$toasted.show(
                        this.__('Error uploading the file. Check your MaxFilesize or permissions'),
                        { type: 'error' }
                    );

                    setTimeout(() => {
                        this.$emit('removeFile', file.id);
                    }, 1000);
                });
        },
    },
};
</script>

<style scoped lang="scss">
.stack-uploads {
    right: 10px;
    bottom: 10px;
    width: 300px;
    z-index: 99;
}
</style>
