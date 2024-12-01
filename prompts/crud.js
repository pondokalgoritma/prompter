function crud() {
    return {
        // pilihan untuk select saat input/update data
        studios: [], 

        records: [],
        filteredRecords: [],
        paginatedRecords: [],
        form: { id: null, title: '', content: '', studio_id: '' },
        isModalOpen: false,
        errors: { title: '', content: '', studio_id: '' },
        currentPage: 1,
        itemsPerPage: 10,
        sortBy: '',
        sortAsc: true,
        searchQuery: '',
        totalPages: 1,
        message: '', 
        timeoutID: null,

        isTeleprompterOpen: false,
        teleprompterRecord: {}, 
        formatedText: '',
        paragraphs: [],
        scrollInterval: null,
        scrollSpeed: 1, 


        init() {
            this.fetchAll();
            this.fetchStudios();
        },

        fetchAll() {
            fetch('crud.php')
            .then(response => response.json())
            .then(data => {
                if (!this.message) {
                    this.setMessage(data.message);
                }
                this.records = data.results;
                this.filteredRecords = data.results;
                this.paginate();
            })
            .then(()=> {this.searchData()})
            .catch(error => {
                this.setMessage(error);
                console.error('Error:', error);
            });
        },

        fetchStudios() {
            fetch('crud.php?action=getStudios')
            .then(response => response.json())
            .then(data => {
                this.studios = data.results;
            })
            .catch(error => {
                this.setMessage(error);
                console.error('Error:', error);
            });
        },

        searchData() {
            const query = this.searchQuery.toLowerCase();
            this.filteredRecords = this.records.filter(record => {
                return (record.title.toLowerCase().includes(query) ||
                        record.content.toLowerCase().includes(query) ||
                        record.studio.toLowerCase().includes(query) ||
                        record.showcase.toString().includes(query));
            });
            this.paginate(); 
        },

        openModal() {
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.form = { id: null, title: '', content: '', studio_id: '' };
            this.errors = { title: '', content: '', studio_id: '' };
        },

        validateForm() {
            let isValid = true;
            this.form.title = this.form.title.trim();
            this.form.content = this.form.content.trim();

            if (!this.form.title) {
                this.errors.title = 'Title is required';
                isValid = false;
            }

            if (!this.form.content) {
                this.errors.content = 'Content is required';
                isValid = false;
            }

            if (!this.form.studio_id) {
                this.errors.studio_id = 'Studio is required';
                isValid = false;
            }

            if (!this.form.showcase) {
                this.errors.showcase = 'Showcase is required';
                isValid = false;
            }

            return isValid;
        },

        clearError(field) {
            this.errors[field] = '';
        },

        saveData() {
            if (!this.validateForm()) return;
            const method = this.form.id ? 'PUT' : 'POST';
        
            fetch('crud.php', {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.form)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Menggunakan .json() untuk memproses respons JSON
            })
            .then(data => {
                console.log('Parsed JSON data:', data); // Debug untuk melihat data JSON
                this.setMessage(data.message);
                this.fetchAll();
                this.closeModal();
            })
            .catch(error => {
                console.error('Caught an error:', error); // Menangani error secara benar
                this.setMessage(error.message || 'An error occurred while saving data');
            });
        },

        editData(record) {
            this.form = { ...record };
            this.openModal();
        },

        deleteData(record) {
            if (window.confirm('Are you sure you want to delete ' + record['title'].toUpperCase() + ' ?')) {
                fetch('crud.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(record)
                })
                .then(response => response.json())
                .then(data => {
                    this.setMessage(data.message);
                    this.fetchAll();
                })
                .catch(error => {
                    this.setMessage(error.message || 'An error occurred while deleting data');
                    console.error('Error:', error);
                });
                
            }
        },

        paginate() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            this.paginatedRecords = this.filteredRecords.slice(start, end);
            this.totalPages = Math.ceil(this.filteredRecords.length / this.itemsPerPage);
        },

        changePage(direction) {
            if (direction === 'prev' && this.currentPage > 1) {
                this.currentPage--;
            } else if (direction === 'next' && this.currentPage < this.totalPages) {
                this.currentPage++;
            }
            this.paginate();
        },

        sortTable(column) {
            if (this.sortBy === column) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortBy = column;
                this.sortAsc = true;
            }
            this.filteredRecords.sort((a, b) => {
                const valA = a[column];
                const valB = b[column];
                return (this.sortAsc ? 1 : -1) * (valA > valB ? 1 : valA < valB ? -1 : 0);
            });
            
            this.paginate();
        },

        setMessage(message) {
            if (this.timeoutID) {
                clearTimeout(this.timeoutID);
            }

            this.message = message;
            this.timeoutID = setTimeout(() => {
                this.message = '';
                this.timeoutID = null; 
            }, 15000); 
        },

        startScrolling() {
            const scroller = this.$refs.teleprompterContent;
            this.scrollInterval = setInterval(() => {
                if (scroller.scrollTop + scroller.clientHeight >= scroller.scrollHeight) {
                    scroller.scrollTop = 0; 

                } else {
                    scroller.scrollTop += this.scrollSpeed;
                }
            }, 50); 
        },

        pausedOnHover() {
            this.scrollSpeed = 0;
        },

        scrollingOnLeave() {
            this.scrollSpeed = 1;
        },
        
        stopScrolling() {
            if (this.scrollInterval) {
                clearInterval(this.scrollInterval);
            }

            const scroller = this.$refs.teleprompterContent;
        },
        
        teleprompter(record) {
            this.teleprompterRecord = record;
            this.isTeleprompterOpen = true;

            this.formatedText = this.teleprompterRecord.content
                .replace(/\*([^*]+)\*/g, '<span class=\'font-bold\'>$1</span>')     // Bold
                .replace(/_([^_]+)_/g, '<span class=\'italic\'>$1</span>')          // Italic
                .replace(/~([^~]+)~/g, '<span class=\'line-through\'>$1</span>')   // Strike-through
                .replace(/###([^#]+)###/g, '<span class=\'font-bold rounded bg-blue-700 px-2\'>$1</span>')    // Blue
                .replace(/##([^#]+)##/g, '<span class=\'font-bold rounded bg-green-700 px-2\'>$1</span>')     // Green
                .replace(/#([^#]+)#/g, '<span class=\'font-bold rounded bg-red-700 px-2\'>$1</span>');        // Red

            this.formatedText = this.formatedText.replace(/\n+/g, '\n');
            this.paragraphs = this.formatedText.split("\n");
        
            this.$nextTick(() => {
                this.$refs.teleprompterContent.scrollTop = 0;
                this.startScrolling();
            });
        },

    
        closeTeleprompter() {
            this.isTeleprompterOpen = false;
            this.stopScrolling();
        },

    };
}