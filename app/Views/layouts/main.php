<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMRS - Sistem Informasi Manajemen Rumah Sakit' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('img/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('img/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('img/apple-touch-icon.png') ?>">
    
    <!-- Bootstrap 4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Include Sidebar -->
    <?= $this->include('layouts/sidebar') ?>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Include Navbar -->
        <?= $this->include('layouts/navbar') ?>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </main>
    
    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 instead of 5 for better modal compatibility -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('js/sidebar.js') ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
