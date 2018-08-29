export default {
    getData(pathToList) {
        return window.axios
            .get('/nova-vendor/infinety-es/nova-filemanager/data', {
                params: { folder: pathToList },
            })
            .then(response => response.data);
    },

    uploadFile() {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/uploads/add')
            .then(response => response.data);
    },

    createFolder(folderName, currentFolder) {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/actions/create-folder', {
                folder: folderName,
                current: currentFolder,
            })
            .then(response => response.data);
    },

    removeDirectory(currentFolder) {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/actions/delete-folder', {
                current: currentFolder,
            })
            .then(response => response.data);
    },

    getInfo(file) {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/actions/get-info', { file: file })
            .then(response => response.data);
    },

    removeFile(file) {
        return window.axios
            .post('/nova-vendor/infinety-es/nova-filemanager/actions/remove-file', { file: file })
            .then(response => response.data);
    },
};
