<template>
    <div class="progress mt-2 w-5/6">
        <div class="progress-bar" :style="progressPercent"></div>
    </div>
</template>

<script>

export default {

    props: {
        file: {
            type: Object,
            default: function() {
                return { };
            },
            required: true,
        },
    },

    data: () => ({
        loading: true,
    }),

    mounted() {
        
    },
    methods: {
        //
    },
    
    computed: {
        progressPercent() {
            return {
                width: this.file.progress + '%'
            }
        }
    }
};
</script>

<style scoped  lang="scss">
    /* PROGRESS */
    .progress {
        background-color: #e5e9eb;
        height: 0.25em;
        position: relative;
    
        .progress-bar {
            animation-duration: 3s;
            animation-name: width;
            background-image: linear-gradient(to right, #4cd964, #5ac8fa, #007aff, #34aadc, #5856d6, #ff2d55);
            background-size: 24em 0.25em;
            height: 100%;
            position: relative;
        }
    }
</style>
