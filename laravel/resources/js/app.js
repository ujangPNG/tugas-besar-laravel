import './bootstrap';
import Swal from 'sweetalert2';

import Alpine from 'alpinejs';

window.Swal = Swal;

// Toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

window.Toast = Toast;

// Handle flash messages
if (sessionStorage.getItem('toast_success')) {
    Toast.fire({
        icon: 'success',
        title: sessionStorage.getItem('toast_success')
    });
    sessionStorage.removeItem('toast_success');
}

if (sessionStorage.getItem('toast_error')) {
    Toast.fire({
        icon: 'error',
        title: sessionStorage.getItem('toast_error')
    });
    sessionStorage.removeItem('toast_error');
}

window.Alpine = Alpine;

Alpine.start();
