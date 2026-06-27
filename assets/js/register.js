const registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
        const fullName = document.getElementById("full_name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        const user = new User(fullName, email, password, confirmPassword);

        if (!user.isValid()) {
            e.preventDefault();
            alert("Please check your details. Password must match and be at least 6 characters.");
        }
    });
}