function profile() {
    return {
        form: { full_name: '', mobile_number: '' },
        errors: { full_name: '', mobile_number: ''},
        message: '',
        timeoutID: null,

        init() {
            fetch('profile_crud.php')
            .then(response => response.json())
            .then(data => {
                this.form.full_name = data.user.full_name;
                this.form.mobile_number = data.user.mobile_number;
            })
            .catch(error => {
                this.setMessage(error);
                console.error('Error:', error);
            });
        },

        saveData() {
            if (!this.validateForm()) return;

            fetch('profile_crud.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.setMessage(data.message);
                    this.init();
                } else {
                    this.setMessage(data.message);
                }
            })
            .catch(error => {
                this.setMessage('An error occurred. Please try again later.');
            });
        },

        validateForm() {
            let isValid = true;
            this.form.full_name = this.form.full_name.trim();
            this.form.mobile_number = this.form.mobile_number.trim();

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

            return isValid;
        },

        clearError(field) {
            this.errors[field] = '';
        },

        setMessage(message) {
            if (this.timeoutID) {
                clearTimeout(this.timeoutID);
            }

            this.message = message;
            this.timeoutID = setTimeout(() => {
                this.message = '';
                this.timeoutID = null; 
            }, 5000); 
        },
    }
}
