import VueLazyload from 'vue-lazyload';

Nova.booting((Vue, router) => {
    Vue.use(VueLazyload, {
        lazyComponent: true,
    });
    router.addRoutes([
        {
            name: 'nova-filemanager',
            path: '/nova-filemanager',
            component: require('./components/Tool'),
        },
    ]);
});
