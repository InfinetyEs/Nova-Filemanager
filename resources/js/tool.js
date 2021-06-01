import VueLazyload from 'vue-lazyload';

Nova.booting((Vue, router) => {
    console.log('Nova.booting');
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
