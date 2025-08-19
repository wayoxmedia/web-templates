// import { msaConfig } from '../../global/js/config.js.php';
import {
  // hideSpinner,
  showSpinner,
  isValidEmail
} from '../../global/js/global.js';


$(document).ready(function () {

  /*********
   Variables
   ********/
  const $form = $('#loginForm');
  const $btnSubmit = $('#btnSubmit');

  // On start, ensure button is disabled. Will be enabled on input change.
  $btnSubmit.attr('disabled', 'disabled');

  const $email = $('#iptEmail');
  const $password = $('#iptPassword');
  const $inputs = $('.input-validate');
  const $errorDisplay = $('#login-error');
  const $btnWrapper = $("#btnWrapper");

  const $chkRememberMe = $("#chkRememberMe");
  const storageKey = "rememberedEmail";
  const savedEmail = localStorage.getItem(storageKey);
  if (savedEmail) {
    $email.val(savedEmail);
    $chkRememberMe.prop("checked", true);
  }
  let tooltipInstance = null; // To hold the tooltip instance

  // Inicializar tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

  /**************
   Event Handlers
   *************/

  // Simple password visibility toggle
  const toggleBtn = document.getElementById('chkTogglePassword');
  const toggleIcon = document.getElementById('toggleIcon');
  const pwdInput = document.getElementById('password');

  if (toggleBtn && toggleIcon && pwdInput) {
    toggleBtn.addEventListener('click', function () {
      const isText = pwdInput.getAttribute('type') === 'text';
      pwdInput.setAttribute('type', isText ? 'password' : 'text');
      toggleIcon.textContent = isText ? 'Show' : 'Hide';
    });
  }
  // Enable button when inputs change.
  $inputs.on('input', function () {
    if ($email.val().length >= 6 && $password.val().length >= 8) {
      $btnSubmit.prop("disabled", false);
      disableTooltip();
    } else {
      // Disable button if criteria is not met.
      $btnSubmit.prop("disabled", true);
      enableTooltip();
    }
  });

  // Handle form submission.
  $form.on('submit', function (e) {
    e.preventDefault();
    // Disable the submit button to prevent multiple clicks.
    $btnSubmit.prop("disabled", true);

    // Reset error display.
    $errorDisplay.addClass('d-none');
    $errorDisplay.html('');

    const email = $email.val();
    const password = $password.val();

    // Validate inputs.
    if (!email || !password) {
      showErrorsInForm('Please enter both email and password.');
      return;
    }

    // Check if email is valid
    if (!isValidEmail(email)) {
      showErrorsInForm('Please enter a valid email address.')
      return;
    }

    // Check email length.
    if (email.length < 6) {
      showErrorsInForm('Email must be at least 6 characters long.');
      return;
    }

    // Check password length.
    if (password.length < 8) {
      showErrorsInForm('Password must be at least 8 characters long.');
      return;
    }

    // Remember me functionality
    if ($chkRememberMe.is(":checked")) {
      localStorage.setItem(storageKey, $email.val());
    } else {
      localStorage.removeItem(storageKey);
    }

    // All good, submit the form.
    showSpinner();
    $btnSubmit.text('Logging in...');
    $form.off('submit').submit();

    /* Via Ajax, to avoid page reload.
    let data = JSON.stringify({
      email: email,
      password: password
    });
    $btnSubmit.attr('disabled', true); // Disable button to prevent multiple clicks
    $.ajax({
      url: msaConfig.apiUrl + '/auth/login',
      method: 'POST',
      contentType: 'application/json',
      dataType: 'json',
      data: data,
      beforeSend: function () {
        $('#login-error').remove(); // removes old message, if any.
        showSpinner();
        $btnSubmit.text('Logging in...');
      },
      success: function (res) {
        hideSpinner();
        // Save token in localStorage.
        localStorage.setItem('token', res['access_token']);
        let in30Mins = Date.now() + res['expires_in'] * 1000;
        localStorage.setItem('token_expires_at', in30Mins.toString());
        window.location.href = 'dashboard.php'; // Redirect OK.
      },
      error: function (xhr) {
        hideSpinner();
        $btnSubmit.text('Sign In');
        console.log('❌ Error de login:', xhr);
        $('form').after(
          `<div id="login-error" style="color: red; margin-top: 1em;">
           ${xhr['responseJSON']?.error || 'Error al iniciar sesión. Verifica tus credenciales.'}
         </div>`
        );
        $btnSubmit.attr('disabled', false); // Re-enable button on error.
      }
    });
    */
  });

  /*********
   Functions
   ********/
  function enableTooltip() {
    if (!tooltipInstance) {
      tooltipInstance = new bootstrap.Tooltip($btnWrapper[0]);
    }
  }

  function disableTooltip() {
    if (tooltipInstance) {
      tooltipInstance.dispose(); // Destruye el tooltip
      tooltipInstance = null;
    }
  }

  function showErrorsInForm(msg) {
    $errorDisplay.removeClass('d-none'); // removes old message, if any.
    $errorDisplay.html(msg);
    $btnSubmit.prop("disabled", false); // Re-enable button on error.
  }
});
