import { msaConfig } from './config.js.php';
import { checkIfLoggedIn, clearToken, logout, refreshIfNearExpiry } from './utils/loginUtilities.js';
import { hideSpinner, showSpinner } from "./global.js";

/**
 * On Document Ready,
 * asynchronously check if the user is logged in
 * and add events to the logout button.
 */
document.addEventListener('DOMContentLoaded', async function () {
  const token = localStorage.getItem('token');
  const logoutBtn = document.getElementById('logout');
  const stillValid = await checkIfLoggedIn(token);
  if (!stillValid) {
    logout();
    return;
  }
  else {
    await refreshIfNearExpiry(token);
    if (window.fromIndex) {
      // if coming from admin/index.php, redirect to dashboard page.
      window.location.href = '/admin/dashboard.php';
    } // else, do nothing, just show the requested page.
  }

  if (logoutBtn) {
    logoutBtn.addEventListener('click', async function () {
      const url = msaConfig.apiUrl + '/auth/logout';
      const lastToken = localStorage.getItem('token');
      showSpinner();

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Authorization': 'Bearer ' + lastToken
          }
        });

        if (!response.ok) {
          console.log('Error en logout:', response.status);
        }
        else {
          console.log('Logout exitoso');
        }
      }
      catch (error) {
        console.error('Fallo de red en logout:', error);
      }
      finally {
        clearToken();
        hideSpinner();
        logout();
      }
    });
  }
});
