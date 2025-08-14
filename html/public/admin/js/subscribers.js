import * as gbl from "./global.js";
import { msaConfig } from "./config.js.php";

$(document).ready(function () {
  async function initializeDataTable() {
    gbl.showSpinner();
    // Get data
    const dataResponse = await fetch(msaConfig.apiUrl + "/subscribers");
    const data = await dataResponse.json();

    // Associate records with geolocations
    data.data = gbl.processGeolocationDataFromArrayOfItems(data.data, 'geo_location');

    new DataTable('#subscribers_list', {
      data: data.data,
      columns: [
        {data: 'address'},
        {
          data: 'address_type',
          render: function (data) {
            if (data === 'e') {
              return 'Email';
            } else if (data === 'p') {
              return 'Telephone';
            } else {
              return 'Unknown "' + data + '"';
            }
          }
        },
        {data: 'user_ip'},
        {data: 'geoData'},
      ],
      layout: {
        topStart: {
          buttons: [
            {
              extend: 'copyHtml5',
              footer: false,
              className: "btn btn-dark m-2-no-left",
              init: function (api, node) {
                $(node).removeClass('dt-button buttons-copy buttons-html5');
                $(node).attr('id', 'copyButton');
              },
            },
            {
              extend: 'excelHtml5',
              footer: false,
              className: "btn btn-dark m-2",
              init: function (api, node) {
                $(node).removeClass('dt-button buttons-excel buttons-html5');
                $(node).attr('id', 'excelButton');
              },
            },
            {
              extend: 'pdfHtml5',
              footer: false,
              className: "btn btn-dark m-2",
              init: function (api, node) {
                $(node).removeClass('dt-button buttons-pdf buttons-html5');
                $(node).attr('id', 'pdfButton');
              },
            },
            {
              extend: "csvHtml5",
              footer: false,
              className: "btn btn-dark m-2",
              init: function (api, node) {
                $(node).removeClass('dt-button buttons-csv buttons-html5');
                $(node).attr('id', 'csvButton');
              },
              title: gbl.reportFilename('SubscribersList', dayjs().format('YYYY-MM-DD'), null, null),
              customize: function (csv) {
                const type = document.getElementById('selSubscriptionType').value;
                let csvHeader = "Store: " + msaConfig.siteName + "\n" +
                  "Subscribers List\n" +
                  "For Subscription Type: " + type + "\n" +
                  "For Date: " + dayjs().format('YYYY-MM-DD') + "\n\n";
                return csvHeader + csv;
              }
            },
            {
              extend: 'print',
              footer: false,
              className: "btn btn-dark m-2",
              init: function (api, node) {
                $(node).removeClass('dt-button buttons-print');
                $(node).attr('id', 'printButton');
              },
            }
          ]
        },
        topEnd: {
          search: {
            placeholder: 'Start typing...',
          }
        },
        bottomEnd: false,
        bottomStart: false,
        bottom: [
          'info',
          {
            pageLength: {
              menu: [10, 25, 50, 100]
            },
          },
          {
            paging: {
              buttons: 3
            }
          }
        ],
      },
      ordering: true,
      order: [[1, "desc"]]
    })
  }

  initializeDataTable()
  .then(() => {
    console.log("Subscribers DataTable initialized successfully.");
  })
  .catch(e => {
    console.log("Error initializing DataTable:", e);
  })
  .finally(() => {
    // Ensure the spinner is hidden after initialization.
    gbl.hideSpinner();
  });
});
