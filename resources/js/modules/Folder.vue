<template>
    <transition name="fade">
        <template v-if="view == 'grid'">
            <div @click="goToFolder"
                 ref="card"
                 :loading="loading"
                 class="card relative flex flex-wrap justify-center border border-lg border-50 overflow-hidden px-0 py-0 cursor-pointer">

                <template v-if="loading">
                    <div class="rounded-lg flex items-center justify-center absolute pin z-50">
                        <loader class="text-60" />
                    </div>
                </template>

                <svg class="w-2/3 h-5/6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#B3C1D1"  d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z"/></svg>

                <div class="h-1/6 w-full text-center text-xs  border-t border-30 bg-50 flex items-center justify-center">
                    {{ file.name }}
                </div>

            </div>
        </template>

        <template v-else>
            <div @click="goToFolder" ref="card" :loading="loading" class="card relative flex flex-wrap border border-lg border-50 overflow-hidden px-0 py-0 cursor-pointer">

                <div class="w-1/4">
                    <template v-if="loading">
                        <div class="rounded-lg flex items-center justify-center absolute pin z-50">
                            <loader class="text-60" />
                        </div>
                    </template>

                    <svg class="w-2/3 h-5/6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#B3C1D1"  d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4z"/></svg>
                </div>

                <div class="w-1/4">
                    {{ file.name }}
                </div>

                <div class="w-1/4">
                    {{ file.date }}
                </div>

                <div class="w-1/4">
                    {{ file.size }}
                </div>

            </div>
        </template>
    </transition>
</template>

<script>
export default {
    props: {
        file: {
            type: Object,
            default: function() {
                return { name: '' };
            },
            required: true,
        },
        view: {
            type: String,
            default: 'grid',
        },
    },

    data: () => ({
        loading: true,
        missing: false,
    }),

    mounted() {
        this.loading = false;
    },

    methods: {
        goToFolder() {
            this.$emit('goToFolderEvent', this.file.path);
        },
    },
};
</script>

<style scoped  lang="scss">
.card {
    padding: 0 !important;

    &:hover {
        > svg {
            opacity: 0.5;
        }
    }
}

.h-5\/6 {
    height: 83.33333%;
}

.h-1\/6 {
    height: 16.66667%;
}

.h-2\/3 {
    height: 75%;
}
</style>
