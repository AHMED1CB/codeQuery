const form = document.forms[0];
const errContainer = document.querySelector(".error_container");

const emailRegx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

function validateEmail(email) {
  return emailRegx.test(email);
}

function showMessage(message) {
  errContainer.innerHTML += `<div id="alert" class="max-w-xl mx-auto mt-6 p-4 rounded-lg shadow-md flex items-start justify-between bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-100">
                    <div class="flex items-center space-x-3">
                        <i class="ph ph-warning"></i>
                        <p class="text-sm font-medium">
                            ${message}
                        </p>
                    </div>
                    <button onclick="document.getElementById('alert').remove()" class="ml-4 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            
                        <i class="ph ph-x-circle text-xl"></i>
                    </button>
                </div>`;
}

form.addEventListener("submit", (event) => { 
     let errors = [];

     if (form.full_name && !form.full_name.value.trim()) {
        errors.push('Invalid Name');
    }

    if (!form.username.value.trim()) {
        errors.push('Invalid Username');
    }

    if (form.email && !validateEmail(form.email.value)) {
        errors.push('Invalid Email');
    }

    if (form.password.value.trim().length < 8) {
        errors.push('Password must be at least 8 characters long');
    }

    if (form.confirm_password && form.password.value !== form.confirm_password.value) {
        errors.push('Passwords do not match');
    }

    if (errors.length > 0) {
        errors.length = 2;
        errContainer.innerHTML = '';
        errors.forEach(error => {
            showMessage(error);
        })
        event.preventDefault();
    }
});
