function announcementsData() {
    return {
        createOpen: false,
        createAction: '',

        editOpen: false,
        editSlug: '',
        editTitle: '',
        editBody: '',
        editStatus: 'public',

        openEdit(slug, title, body, status) {
            this.editSlug = slug;
            this.editTitle = title;
            this.editBody = body;
            this.editStatus = status;
            this.editOpen = true;
        },

    }
}