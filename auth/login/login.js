function login() {
    return {
        form: { user_name: '', password: '' },
        errors: { user_name: '', password: ''},
        errorMessage: '',
        successMessage: '',
        showLogin: false,

        verify() {
            if (!this.validateForm()) return;

            fetch('auth/login/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.successMessage = data.message;
                    this.errorMessage = '';
                    this.showLogin = false;
                    window.location.href = '/prompts'
                } else {
                    this.errorMessage = data.message;
                    this.successMessage = '';
                }
            })
            .catch(error => {
                this.errorMessage = 'An error occurred. Please try again later.';
                this.successMessage = '';
            });
        },

        validateForm() {
            let isValid = true;
            this.form.user_name = this.form.user_name.trim();
            this.form.password = this.form.password.trim();

            if (!this.form.user_name) {
                this.errors.user_name = 'User name is required';
                isValid = false;
            }

            if (!this.form.password) {
                this.errors.password = 'Password is required';
                isValid = false;
            }

            return isValid;
        },

        clearError(field) {
            this.errors[field] = '';
        },

        openModal(){
            this.showLogin = true;
            this.form = { user_name: '', password: '' };
        },

        closeModal(){
            this.showLogin = false;
        },
    }
}
