import { msaConfig } from './config.js.php';
import { hideSpinner, showSpinner } from './global.js';

$(document).ready(function () {
  // Handle form submission
  $('form').on('submit', function (e) {
    e.preventDefault();

    let email = $('#iptEmail').val();
    let password = $('#iptPassword').val();
    let data = JSON.stringify({
      email: email,
      password: password
    });

    let btnSubmit = $('#btnSubmit');
    btnSubmit.attr('disabled', true); // Disable button to prevent multiple clicks
    $.ajax({
      url: msaConfig.apiUrl + '/auth/login',
      method: 'POST',
      contentType: 'application/json',
      dataType: 'json',
      data: data,
      beforeSend: function () {
        $('#login-error').remove(); // elimina mensaje previo, si existe.
        showSpinner();
        btnSubmit.text('Logging in...');
      },
      success: function (res) {
        hideSpinner();
        // Save token in localStorage
        // TODO: rename to 'token_XXX' (the store name or id) using a var from config.
        localStorage.setItem('token', res['access_token']);
        let in30Mins = Date.now() + res['expires_in'] * 1000;
        localStorage.setItem('token_expires_at', in30Mins.toString());
        window.location.href = 'dashboard.php'; // Redirect OK.
      },
      error: function (xhr) {
        hideSpinner();
        btnSubmit.text('Sign In');
        console.log('❌ Error de login:', xhr);
        $('form').after(
          `<div id="login-error" style="color: red; margin-top: 1em;">
           ${xhr['responseJSON']?.error || 'Error al iniciar sesión. Verifica tus credenciales.'}
         </div>`
        );
        btnSubmit.attr('disabled', false); // Re-enable button on error.
      }
    });
  });
});

/*
// Check if needed, this is for the admin login page
document.addEventListener('DOMContentLoaded', function () {
  // Prevent double submissions
  const form = document.querySelector('form[action="login.php"]');
  const btn  = document.getElementById('loginBtn');
  if (form && btn) {
    form.addEventListener('submit', function () {
      btn.setAttribute('disabled', 'disabled');
    });
  }

  // Simple password visibility toggle
  const toggleBtn  = document.getElementById('togglePassword');
  const toggleIcon = document.getElementById('toggleIcon');
  const pwdInput   = document.getElementById('password');

  if (toggleBtn && toggleIcon && pwdInput) {
    toggleBtn.addEventListener('click', function () {
      const isText = pwdInput.getAttribute('type') === 'text';
      pwdInput.setAttribute('type', isText ? 'password' : 'text');
      toggleIcon.textContent = isText ? 'Show' : 'Hide';
    });
  }
});
*/
