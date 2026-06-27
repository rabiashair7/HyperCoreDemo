class User {
    constructor(fullName, email, password, confirmPassword) {
        this.fullName = fullName;
        this.email = email;
        this.password = password;
        this.confirmPassword = confirmPassword;
    }

    isValid() {
        return (
            this.fullName.length >= 3 &&
            this.email.includes("@") &&
            this.password.length >= 6 &&
            this.password === this.confirmPassword
        );
    }
}