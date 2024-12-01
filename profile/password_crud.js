function password() {
    return {
        form: { current_password: '', new_password: '', confirm_password: '', },
        errors: { current_password: '', new_password: '', confirm_password: ''},
        message: '',
        timeoutID: null,

        init() {
            this.form.current_password = '';
            this.form.new_password = '';
            this.form.confirm_password = '';
        },

        saveData() {
            if (!this.validateForm()) return;

            fetch('password_crud.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                this.setMessage(data.message) 
            })
            .catch(error => {
                this.setMessage('An error occurred. Please try again later.');
            });
        },

        validateForm() {
            let isValid = true;
            this.form.current_password = this.form.current_password.trim();
            this.form.new_password = this.form.new_password.trim();
            this.form.confirm_password = this.form.confirm_password.trim();

            if (!this.form.current_password) {
                this.errors.current_password = 'Current password is required';
                isValid = false;
            }

            if (!this.form.new_password) {
                this.errors.new_password = 'New password is required';
                isValid = false;
            }

            if (!this.form.confirm_password) {
                this.errors.confirm_password = 'Confirm password is required';
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
            }, 15000); 
        },
    }
}
