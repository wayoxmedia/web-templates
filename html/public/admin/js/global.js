import { msaConfig } from './config.js.php';

let spinnerCount = 0;

/**
 * Show Spinner and blocks user interaction.
 */
export function showSpinner() {
  spinnerCount++;
  const overlay = document.getElementById('global-spinner-overlay');
  if (overlay) overlay.style.display = 'flex';
}

/**
 * Hide Spinner and allows user interaction.
 */
export function hideSpinner() {
  const overlay = document.getElementById('global-spinner-overlay');
  if (spinnerCount > 0) spinnerCount--;
  // If there are still spinners, do not hide the overlay.
  if (spinnerCount === 0 && overlay) overlay.style.display = 'none';
}

/**
 * Generate a report filename based on the provided parameters.
 *
 * @param type
 * @param startDate
 * @param endDate
 * @param suffix
 * @returns {string}
 */
export function reportFilename(type = "report", startDate = null, endDate = null, suffix = null) {
  let scn = msaConfig.siteCodeName || '';

  let startDateFmt = startDate && dayjs(startDate).isValid()
    ? dayjs(startDate).format("YYYY-MM-DD")
    : startDate;
  let endDateFmt = endDate
    ? (dayjs(endDate).isValid()
      ? `_to_${dayjs(endDate).format("YYYY-MM-DD")}`
      : `_to_${endDate}`)
    : "";

  suffix = suffix ? `_${suffix}`: "";

  return `${scn}_${type}_${startDateFmt}${endDateFmt}${suffix}`.replace(/ /g,"_");
}

/**
 * Check if the given item is null, undefined, or an empty string.
 * @param item
 * @returns {boolean}
 */
export function isNUE(item) {
  return item === null || item === undefined || item === '';
}

/**
 * Process geolocation data for each record in the provided item array.
 * @param item
 * @param recordName
 * @returns {*}
 */
export function processGeolocationDataFromArrayOfItems(item, recordName) {
  item.forEach((record) => {
    let geoData = (isValidJson(record[recordName]))
      ? JSON.parse(record[recordName])
      : record[recordName]
    record.geoData = generateGeolocationString(geoData);
  });

  return item;
}

/**
 * Generate a string representation of geolocation data.
 * @param item
 * @returns {string|string}
 */
export function generateGeolocationString(item) {
  return isNUE(item)
    ? 'No GeoLocation'
    : `${item?.['city'] || 'No City'}, ${item?.['regionName'] || 'No State'}, ${item?.['country'] || 'No Country'}`;
}

/**
 * Determine if the provided data is a valid JSON object or string.
 * Keep in mind that for this function, null will return false.
 *
 * @param data
 * returns {boolean}
 */
export function isValidJson(data) {
  let test;
  if (typeof data === 'string') {
    try {
      test = JSON.parse(data);
    } catch (e) {
      console.log('Invalid JSON string:', e);
      return false;
    }
  }
  return typeof test === 'object' && data !== null;
}
