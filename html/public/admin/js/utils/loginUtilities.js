import {msaConfig} from '../config.js.php';
import {hideSpinner, showSpinner} from '../global.js';

/**
 * Check if the user is logged in
 */
export function isLoggedIn(token) {
  return !!token;
}

/**
 * Check if the token is expired
 */
export function isTokenExpired() {
  let expiresAfter = parseInt(localStorage.getItem('token_expires_at'), 10) || 0;
  return Date.now() > expiresAfter;
}

/**
 * Check if the user is already logged in.
 *
 * If the user is not logged in or the token has expired,
 * clean the localStorage items and redirect to login page.
 */
export async function checkIfLoggedIn(token) {
  if (!isLoggedIn(token) || isTokenExpired()) {
    // No token or expired, cleanup and logout (redirect to login page).
    clearToken();
    logout();
  }
  else {
    // If the user is logged in, refresh the token
    return true;
  }
}

/**
 * Set the token in localStorage.
 * @param newToken
 */
export function setToken(newToken) {
  let refreshedToken = newToken['access_token'];
  localStorage.setItem('token', refreshedToken);
  let xMins = Date.now() + newToken['expires_in'] * 1000;
  localStorage.setItem('token_expires_at', xMins.toString());
}

/**
 * Refresh the token.
 * @param token
 * @return {Promise<boolean>} Returns true if the token was refreshed successfully, false otherwise.
 */
export async function refreshToken(token) {
  if (!token) {
    console.warn('No token provided for refresh');
    clearToken();
    return false;
  }

  showSpinner();

  try {
    const response = await fetch(msaConfig.apiUrl + '/auth/refresh', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    if (!response.ok) {
      throw new Error('Token inválido o expirado');
    }

    const data = await response.json();
    setToken(data);
    return true;
  }
  catch (error) {
    console.warn('Error refrescando token:', error);
    clearToken();
    logout();
    return false;
  }
  finally {
    hideSpinner();
  }
}

/**
 * If the token expires in less than 5 minutes, try refreshing.
 */
export function refreshIfNearExpiry(token) {
  const expiresAt = parseInt(localStorage.getItem('token_expires_at'), 10);
  const fiveMinutesFromNow = Date.now() + 5 * 60 * 1000;

  if (expiresAt && expiresAt <= fiveMinutesFromNow) {
    return refreshToken(token);
  }

  return Promise.resolve(true); // No need to refresh.
}

/**
 * Clear the token from localStorage.
 */
export function clearToken() {
  localStorage.removeItem('token');
  localStorage.removeItem('token_expires_at');
}

/**
 * Logout the user by clearing the token and redirecting to the login page.
 */
export function logout() {
  window.location.href = '/admin/login.php';
}
