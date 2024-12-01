function crud() {
    return {
        records: [],
        filteredRecords: [],
        paginatedRecords: [],
        form: { id: null, name: '' },
        isModalOpen: false,
        errors: { name: '' },
        currentPage: 1,
        itemsPerPage: 10,
        sortBy: '',
        sortAsc: true,
        searchQuery: '',
        totalPages: 1,
        message: '',
        timeoutID: null,

        init() {
            this.fetchAll();
        },

        fetchAll() {
            fetch('crud.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch data');
                    }
                    return response.json();
                })
                .then(data => {
                    this.setMessage(data.message);
                    this.records = data.results;
                    this.filteredRecords = data.results;
                    this.paginate();
                })
                .then(() => this.searchData())
                .catch(error => {
                    this.setMessage(error.message || 'An error occurred while fetching data');
                    console.error('Error:', error);
                });
        },

        searchData() {
            const query = this.searchQuery.toLowerCase();
            this.filteredRecords = this.records.filter(record =>
                record.name.toLowerCase().includes(query)
            );
            this.paginate();
        },

        openModal() {
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.form = { id: null, name: '' };
            this.errors = { name: '' };
        },

        validateForm() {
            let isValid = true;
            this.form.name = this.form.name.trim();

            if (!this.form.name) {
                this.errors.name = 'Studio name is required';
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
                        throw new Error('Failed to save data');
                    }
                    return response.json();
                })
                .then(data => {
                    this.setMessage(data.message);
                    this.fetchAll();
                    this.closeModal();
                })
                .catch(error => {
                    this.setMessage(error.message || 'An error occurred while saving data');
                    console.error('Error:', error);
                });
        },

        editData(record) {
            this.form = { ...record };
            this.openModal();
        },

        deleteData(record) {
            if (window.confirm('Are you sure you want to delete ' + record['name'].toUpperCase() + ' ?')) {
                fetch('crud.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(record)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to delete data');
                        }
                        return response.json();
                    })
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
        }
    };
}
