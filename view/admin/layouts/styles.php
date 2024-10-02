<!-- General CSS Files -->
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/app.min.css">

<!-- Template CSS -->
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/style.css">
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/components.css">

<!-- Custom style CSS -->
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/css/custom.css">
<link rel='shortcut icon' type='image/x-icon' href='<?= URL_BASE_HOST ?>/public/template/assets/img/favicon.ico' />

<!-- Select2 -->
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/bundles/select2/dist/css/select2.min.css">

<!-- Jquery -->
<script src="<?= URL_BASE_HOST ?>/public/assets/js/axios.min.js"></script>
<script src="<?= URL_BASE_HOST ?>/public/assets/js/ajax.min.js"></script>

<!-- Jquery -->
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/bundles/datatables/datatables.min.css">

<!-- Toastr -->
<link rel="stylesheet" href="<?= URL_BASE_HOST ?>/public/template/assets/bundles/izitoast/css/iziToast.min.css" />

<style>
    /* Estilos para as mensagens de erro */
    .error.invalid-feedback {
        display: block;
        margin-top: 5px;
        color: #dc3545;
    }

    .select2-container--default .select2-selection--single.is-invalid {
        border-color: #dc3545;
    }

    .select2-container--default .select2-selection--single.is-invalid .select2-selection__rendered {
        color: #dc3545;
    }

    .select2-container--default .select2-selection--single.is-invalid .select2-selection__arrow b {
        border-color: #dc3545 transparent transparent transparent;
        height: calc(1.8125rem + 2px);
    }

    .select2-container--default .select2-selection--multiple.is-invalid {
        border-color: #dc3545;
    }
</style>