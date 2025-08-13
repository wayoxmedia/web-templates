<?php
/**
 * @var string $title
 */
$titleStr = (isset($title)) ? $title . ' - ' : '';
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $titleStr . SITE_NAME ?> - My Store Panel</title>
  <!-- CSS -->
  <link rel="shortcut icon" type="image/png" href="../favicon.svg" />
  <link rel="stylesheet" href="../static/admin/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.css" />
  <link rel="stylesheet" href="../static/admin/css/custom.css" />

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
          integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <script type="module" src="../static/admin/js/global.js"></script>
  <script type="module" src="../static/admin/js/isLoggedIn.js"></script>
</head>
