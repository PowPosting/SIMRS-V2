<!-- Main Header/Navbar -->
<header class="main-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-left">
                <button class="btn btn-outline-secondary d-md-none" id="mobileSidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
            </div>
            
            <!-- Real-time Clock (Center) -->
            <div class="header-center d-none d-lg-block">
                <div class="text-white">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="navbar-clock"></span>
                </div>
            </div>
            
            <!-- Header Right -->
            <div class="header-right">
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-avatar mr-2">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="user-info d-none d-md-block text-left">
                            <span class="user-name"><?= session()->get('nama_lengkap') ?? 'User' ?></span>
                            <small class="user-role d-block text-muted"><?= ucfirst(session()->get('role') ?? 'Role') ?></small>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                                <div class="ml-2">
                                    <div><?= session()->get('nama_lengkap') ?? 'User' ?></div>
                                    <small class="text-muted"><?= ucfirst(session()->get('role') ?? 'Role') ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url('/profile') ?>">
                            <i class="fas fa-user-cog mr-2"></i>Profile Saya
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item logout" href="<?= base_url('/logout') ?>">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
