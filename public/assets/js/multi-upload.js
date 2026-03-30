// Multi Upload Logic
function {{ $jsName }}() {
    return {
        files: [],
        dragging: false,
        maxSize: {{ $maxSize }},
        maxFiles: {{ $maxFiles }},

        onInputChange(e) {
            this.addFiles([...e.target.files]);
        },

        onDrop(e) {
            this.dragging = false;
            this.addFiles([...e.dataTransfer.files]);
        },

        addFiles(newFiles) {
            newFiles.forEach(file => {
                if (this.files.length >= this.maxFiles) return;

                const sizeMB = file.size / 1024 / 1024;
                if (sizeMB > this.maxSize) {
                    alert(`${file.name} melebihi ${this.maxSize}MB`);
                    return;
                }

                this.files.push({
                    name: file.name,
                    sizeLabel: sizeMB < 1
                        ? (file.size / 1024).toFixed(1) + ' KB'
                        : sizeMB.toFixed(1) + ' MB',
                    raw: file
                });
            });

            this.syncInput();
        },

        removeFile(i) {
            this.files.splice(i, 1);
            this.syncInput();
        },

        clearAll() {
            this.files = [];
            this.syncInput();
        },

        syncInput() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f.raw));
            this.$refs.fileInput.files = dt.files;
        }
    }
}
