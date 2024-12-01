function crud() {
    return {
        studios: [], 
        records: [],
        filteredRecords: [],
        paginatedRecords: [],
        form: { id: null, user_name: '', full_name: '', mobile_number: '', studio_id: '' },
        isModalOpen: false,
        errors: { user_name: '', full_name: '', mobile_number: '', studio_id: '' },
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
            this.fetchStudios();
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

        fetchStudios() {
            fetch('crud.php?action=getStudios')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch studios');
                    }
                    return response.json();
                })
                .then(data => {
                    this.studios = data.results;
                })
                .catch(error => {
                    this.setMessage(error.message || 'An error occurred while fetching studios');
                    console.error('Error:', error);
                });
        },

        searchData() {
            const query = this.searchQuery.toLowerCase();
            this.filteredRecords = this.records.filter(record =>
                record.user_name.toLowerCase().includes(query) ||
                record.full_name.toLowerCase().includes(query) ||
                record.studio.toLowerCase().includes(query) ||
                record.mobile_number.includes(query)
            );
            this.paginate();
        },

        openModal() {
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.form = { id: null, user_name: '', full_name: '', mobile_number: '', studio_id: '' };
            this.errors = { user_name: '', full_name: '', mobile_number: '', studio_id: '' };
        },

        validateForm() {
            let isValid = true;
            this.form.user_name = this.form.user_name.trim();
            this.form.full_name = this.form.full_name.trim();
            this.form.mobile_number = this.form.mobile_number.trim();

            if (!this.form.user_name) {
                this.errors.user_name = 'User name is required';
                isValid = false;
            }

            if (!this.form.full_name) {
                this.errors.full_name = 'Full name is required';
                isValid = false;
            }

            if (!this.form.mobile_number) {
                this.errors.mobile_number = 'Mobile number is required';
                isValid = false;
            } else if (!/^\d+$/.test(this.form.mobile_number)) {
                this.errors.mobile_number = 'Mobile number must contain only digits';
                isValid = false;
            } else if (this.form.mobile_number.length < 10 || this.form.mobile_number.length > 15) {
                this.errors.mobile_number = 'Mobile number must be between 10 and 15 digits';
                isValid = false;
            }

            if (!this.form.studio_id) {
                this.errors.studio_id = 'Studio is required';
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

        resetPassword(record) {
            if (window.confirm(`Are you sure you want to reset ${record.full_name.toUpperCase()}'s password?`)) {
                const requestData = {
                    ...record,
                    action: 'resetPassword'
                };

                fetch('crud.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(requestData)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to reset password');
                        }
                        return response.json();
                    })
                    .then(data => {
                        this.setMessage(data.message);
                        this.fetchAll();
                    })
                    .catch(error => {
                        this.setMessage(error.message || 'An error occurred while resetting password');
                        console.error('Error:', error);
                    });
            }
        },

        deleteData(record) {
            if (window.confirm(`Are you sure you want to delete ${record.user_name.toUpperCase()}?`)) {
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
