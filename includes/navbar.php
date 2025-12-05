<?php
// Dapatkan nama file saat ini tanpa path
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<!-- Navbar Gen Z Modern -->
<nav class="navbar-genz">
    <div class="container-fluid">
        <div class="navbar-genz-content">
            <!-- Brand -->
            <a class="navbar-brand-genz" href="<?php echo ($_SESSION['role'] == 'kadet') ? 'dashboard_piket.php' : 'dashboard_admin.php'; ?>">
                <i class="bi bi-rocket-takeoff"></i>
                <span>Piket Batalyon</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggle-genz" type="button" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Menu Items -->
            <div class="navbar-menu-genz" id="navMenu">
                <ul class="nav-list">
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item-genz">
                            <a href="dashboard_admin.php" class="nav-link-genz <?php echo ($current_page == 'dashboard_admin.php') ? 'active' : ''; ?>">
                                <i class="bi bi-person-gear"></i>
                                <span>Manajemen Pengguna</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="manage_batalyon.php" class="nav-link-genz <?php echo ($current_page == 'manage_batalyon.php') ? 'active' : ''; ?>">
                                <i class="bi bi-building"></i>
                                <span>Master Batalyon</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="dashboard_organik.php" class="nav-link-genz <?php echo ($current_page == 'dashboard_organik.php') ? 'active' : ''; ?>">
                                <i class="bi bi-graph-up"></i>
                                <span>Pantau Rekap</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="rekap_grafik.php" class="nav-link-genz <?php echo ($current_page == 'rekap_grafik.php') ? 'active' : ''; ?>">
                                <i class="bi bi-bar-chart"></i>
                                <span>Grafik Rekap</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="lihat_roster.php" class="nav-link-genz <?php echo ($current_page == 'lihat_roster.php') ? 'active' : ''; ?>">
                                <i class="bi bi-calendar2-week"></i>
                                <span>Jadwal Piket</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="lihat_rencana.php" class="nav-link-genz <?php echo ($current_page == 'lihat_rencana.php') ? 'active' : ''; ?>">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Rencana</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="lihat_dokumentasi.php" class="nav-link-genz <?php echo ($current_page == 'lihat_dokumentasi.php') ? 'active' : ''; ?>">
                                <i class="bi bi-image"></i>
                                <span>Dokumentasi</span>
                            </a>
                        </li>

                    <?php elseif ($_SESSION['role'] == 'organik'): ?>
                        <li class="nav-item-genz">
                            <a href="dashboard_organik.php" class="nav-link-genz <?php echo ($current_page == 'dashboard_organik.php') ? 'active' : ''; ?>">
                                <i class="bi bi-graph-up"></i>
                                <span>Pantau Rekap</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="rekap_grafik.php" class="nav-link-genz <?php echo ($current_page == 'rekap_grafik.php') ? 'active' : ''; ?>">
                                <i class="bi bi-bar-chart"></i>
                                <span>Grafik Rekap</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="lihat_roster.php" class="nav-link-genz <?php echo ($current_page == 'lihat_roster.php') ? 'active' : ''; ?>">
                                <i class="bi bi-calendar2-week"></i>
                                <span>Jadwal Piket</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="lihat_rencana.php" class="nav-link-genz <?php echo ($current_page == 'lihat_rencana.php') ? 'active' : ''; ?>">
                                <i class="bi bi-clipboard-check"></i>
                                <span>Rencana</span>
                            </a>
                        </li>
                        <li class="nav-item-genz">
                            <a href="lihat_dokumentasi.php" class="nav-link-genz <?php echo ($current_page == 'lihat_dokumentasi.php') ? 'active' : ''; ?>">
                                <i class="bi bi-image"></i>
                                <span>Dokumentasi</span>
                            </a>
                        </li>

                    <?php elseif ($_SESSION['role'] == 'kadet'): ?>
                        <li class="nav-item-genz">
                            <a href="dashboard_piket.php" class="nav-link-genz <?php echo ($current_page == 'dashboard_piket.php') ? 'active' : ''; ?>">
                                <i class="bi bi-house-heart"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- User Profile Dropdown -->
                <div class="navbar-user-genz">
                    <div class="user-dropdown">
                        <button class="user-btn" id="userBtn" type="button">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama']); ?>&background=800000&color=FFD700" 
                                 alt="Avatar" class="user-avatar">
                            <div class="user-info">
                                <span class="user-name"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                                <span class="user-role"><?php echo ucfirst($_SESSION['role']); ?></span>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu-genz" id="userMenu">
                            <a href="javascript:void(0)" class="dropdown-item-genz">
                                <i class="bi bi-gear"></i>
                                <span>Ganti Password</span>
                            </a>
                            <div class="divider"></div>
                            <a href="../process/logout.php" class="dropdown-item-genz logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Navbar Styling & Scripts -->
<style>
    /* Navbar Gen Z Modern */
    .navbar-genz {
        background: linear-gradient(135deg, #800000 0%, #2b0000 100%);
        padding: 0.75rem 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 8px 32px rgba(128, 0, 0, 0.15);
        backdrop-filter: blur(10px);
    }

    .navbar-genz-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .navbar-brand-genz {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #FFD700;
        font-weight: 700;
        font-size: 1.3rem;
        text-decoration: none;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .navbar-brand-genz:hover {
        color: #fff;
        transform: scale(1.05);
    }

    .navbar-brand-genz i {
        font-size: 1.5rem;
    }

    /* Navbar Menu */
    .navbar-menu-genz {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        justify-content: space-between;
    }

    .nav-list {
        display: flex;
        list-style: none;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-item-genz {
        margin: 0;
    }

    .nav-link-genz {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        white-space: nowrap;
    }

    .nav-link-genz:hover {
        color: #FFD700;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .nav-link-genz.active {
        background: #FFD700;
        color: #2b0000;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
    }

    .nav-link-genz i {
        font-size: 1.1rem;
    }

    /* User Profile Dropdown */
    .navbar-user-genz {
        margin-left: auto;
    }

    .user-dropdown {
        position: relative;
    }

    .user-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        font-size: 0.9rem;
    }

    .user-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: #FFD700;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 2px solid #FFD700;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        text-align: left;
        gap: 0.2rem;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-role {
        font-size: 0.75rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Dropdown Menu */
    .dropdown-menu-genz {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        margin-top: 0.5rem;
        min-width: 200px;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        overflow: hidden;
        z-index: 1001;
    }

    .dropdown-menu-genz.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .dropdown-item-genz {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        color: #2b0000;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .dropdown-item-genz:hover {
        background: #F0F2F5;
        padding-left: 1.5rem;
        color: #800000;
    }

    .dropdown-item-genz.logout {
        color: #dc3545;
    }

    .dropdown-item-genz.logout:hover {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .divider {
        height: 1px;
        background: #e0e0e0;
        margin: 0.5rem 0;
    }

    /* Mobile Toggle */
    .navbar-toggle-genz {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
    }

    .navbar-toggle-genz span {
        width: 25px;
        height: 3px;
        background: white;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .navbar-toggle-genz.active span:nth-child(1) {
        transform: rotate(45deg) translate(8px, 8px);
    }

    .navbar-toggle-genz.active span:nth-child(2) {
        opacity: 0;
    }

    .navbar-toggle-genz.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -7px);
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .navbar-genz-content {
            gap: 1rem;
        }

        .navbar-menu-genz {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #800000 0%, #2b0000 100%);
            flex-direction: column;
            gap: 0;
            margin-top: 0.75rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            border-radius: 0 0 15px 15px;
            padding: 0;
        }

        .navbar-menu-genz.active {
            max-height: 500px;
            padding: 1rem 0;
        }

        .nav-list {
            flex-direction: column;
            width: 100%;
            gap: 0;
            justify-content: flex-start;
        }

        .nav-item-genz {
            width: 100%;
        }

        .nav-link-genz {
            border-radius: 0;
            padding: 0.75rem 1.5rem;
            margin: 0;
        }

        .navbar-user-genz {
            margin-left: 0;
            width: 100%;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-toggle-genz {
            display: flex;
        }
    }

    @media (max-width: 576px) {
        .navbar-brand-genz span {
            display: none;
        }

        .nav-link-genz span {
            display: none;
        }

        .nav-link-genz {
            padding: 0.6rem 0.8rem;
        }

        .user-info {
            display: none;
        }

        .user-btn {
            padding: 0.5rem;
        }
    }
</style>

<script>
    // Mobile Menu Toggle
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    const userBtn = document.getElementById('userBtn');
    const userMenu = document.getElementById('userMenu');

    navToggle.addEventListener('click', function() {
        navToggle.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    // User Dropdown Toggle
    userBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        userMenu.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.remove('active');
        }
        if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
            navToggle.classList.remove('active');
            navMenu.classList.remove('active');
        }
    });

    // Close menu on link click
    document.querySelectorAll('.nav-link-genz').forEach(link => {
        link.addEventListener('click', function() {
            navToggle.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
</script>