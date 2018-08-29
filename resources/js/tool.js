Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'nova-filemanager',
            path: '/nova-filemanager',
            component: require('./components/Tool'),
        },
    ]);
});
