<template>
    <transition name="fade">

        <div
            ref="card"
            :loading="loading"
            :class="css"
        >
            <template v-if="loading">
                <div class="rounded-lg flex items-center justify-center absolute pin z-50">
                    <loader class="text-60" />
                </div>
            </template>

            <div ref="imageDiv" class="image-block block w-full h-5/6">

            </div>

            <div class="missing p-8" v-if="missing">
                <p class="text-center leading-normal">
                    <a :href="file.name" class="text-primary dim" target="_blank">{{__('This image')}}</a> {{__('could not be found.')}}
                </p>
            </div>
        </div>
    </transition>
</template>

<script>
import { Minimum } from 'laravel-nova';

export default {
    components: {
        //
    },

    props: {
        file: {
            type: Object,
            default: function() {
                return { name: '' };
            },
            required: true,
        },
        css: {
            type: String,
            default:
                'card relative w-2/3 flex flex-wrap justify-center overflow-hidden px-0 py-0',
            required: false,
        },
    },

    data: () => ({
        loading: true,
        missing: false,
    }),

    mounted() {
        Minimum(
            window.axios.get(this.file.image, {
                responseType: 'blob',
            })
        )
            .then(({ headers, data }) => {
                const blob = new Blob([data], { type: headers['content-type'] });
                let newImage = new Image();
                newImage.src = window.URL.createObjectURL(blob);
                newImage.className = 'block w-full';
                newImage.draggable = false;
                this.$refs.imageDiv.appendChild(newImage);
                this.loading = false;
            })
            .catch(error => {
                if (error) {
                    this.missing = true;
                    this.$emit('missing', true);
                    this.loading = false;
                }
            });
    },
    methods: {
        //
    },
};
</script>

<style scoped  lang="scss">
.card {
    padding: 0 !important;
    box-shadow: none;
    border-radius: 0;
}

.h-5\/6 {
    height: 83.33333%;
}

.h-1\/6 {
    height: 16.66667%;
}
</style>

<style>
.svg-mime {
    width: 80px;
    height: 75%;
    fill: #62676d;
}
</style>
