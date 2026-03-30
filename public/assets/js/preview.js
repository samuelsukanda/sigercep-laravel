// Preview Modal Component
function previewModal() {
    return {
        show: false,
        fileUrl: "",
        isImage: true,

        openModal(url, ext) {
            this.fileUrl = url;
            this.isImage = ["jpg", "jpeg", "png"].includes(ext);
            this.show = true;
        },

        closeModal() {
            this.show = false;
            this.fileUrl = "";
        },
    };
}
