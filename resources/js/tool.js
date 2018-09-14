Nova.booting((Vue, router) => {
	
	Vue.config.devtools = true

    router.addRoutes([
        {
            name: 'nova-filemanager',
            path: '/nova-filemanager',
            component: require('./components/Tool'),
        },
    ]);
});
