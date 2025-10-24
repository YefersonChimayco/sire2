<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>SIRE - Sistema Integral de Registro Estudiantil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Sistema Integrado de Gestión Institucional - IES Huanta" name="description" />
    <meta content="AnibalYucraC" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo BASE_URL ?>src/view/pp/assets/images/favicon.ico">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Plugins css -->
    <script src="<?php echo BASE_URL ?>src/view/js/principal.js"></script>
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/select.bootstrap4.css" rel="stylesheet" type="text/css" />
    
    <!-- Sweet Alerts css -->
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/theme.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/include/styles.css" rel="stylesheet" type="text/css" />

    <style>
        /* ===== ESTILOS MINIMALISTAS ===== */
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #2c5aa0;
            --accent-color: #e53935;
            --text-light: #f5f5f5;
            --bg-light: #f8f9fa;
            --border-color: #e0e0e0;
        }

        #page-topbar {
            background: var(--text-light);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.8rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand-box .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .navbar-brand-box .logo i {
            font-size: 1.5rem;
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .navbar-brand-box .logo:hover {
            color: var(--secondary-color);
        }

        .header-item {
            background: transparent !important;
            border: 1px solid var(--border-color) !important;
            color: var(--primary-color) !important;
            border-radius: 4px !important;
            padding: 6px 12px !important;
            transition: all 0.2s ease !important;
            font-weight: 500;
        }

        .header-item:hover {
            background: var(--bg-light) !important;
            border-color: var(--secondary-color) !important;
        }

        .header-profile-user {
            border: 2px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .header-profile-user:hover {
            border-color: var(--secondary-color);
        }

        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            background: var(--text-light);
        }

        .dropdown-item {
            color: var(--primary-color);
            padding: 8px 16px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        .dropdown-item:hover {
            background: var(--bg-light);
            color: var(--secondary-color);
        }

        /* ===== NAVEGACIÓN MINIMALISTA ===== */
        .topnav {
            background: var(--text-light);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-light .navbar-nav .nav-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            padding: 12px 16px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        .navbar-light .navbar-nav .nav-link i {
            font-size: 1rem;
        }

        .dropdown-toggle::after {
            transition: transform 0.2s ease;
        }

        .dropdown.show .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        /* ===== MENÚ DROPDOWN MINIMALISTA ===== */
        .topnav-menu .dropdown-menu {
            background: var(--text-light);
            border-radius: 4px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .topnav-menu .dropdown-item {
            color: var(--primary-color);
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .topnav-menu .dropdown-item:hover {
            background: var(--bg-light);
            color: var(--secondary-color);
        }

        /* ===== POPUP DE CARGA MINIMALISTA ===== */
        #popup-carga .popup-overlay {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }

        #popup-carga .popup-content {
            background: var(--text-light);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 30px;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        #popup-carga .spinner {
            border: 2px solid rgba(0,0,0,0.1);
            border-top: 2px solid var(--secondary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .navbar-brand-box .logo span {
                font-size: 1rem;
            }
            
            .header-item {
                padding: 5px 10px !important;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 767.98px) {
            .navbar-brand-box .logo span {
                display: none;
            }
            
            #page-topbar {
                padding: 0.5rem 0;
            }
        }
    </style>

    <script>
        const base_url = '<?php echo BASE_URL; ?>';
        const base_url_server = '<?php echo BASE_URL_SERVER; ?>';
        const session_session = '<?php echo $_SESSION['sesion_id']; ?>';
        const session_ies = '<?php echo $_SESSION['sesion_ies']; ?>';
        const token_token = '<?php echo $_SESSION['sesion_token']; ?>';
    </script>
    <?php date_default_timezone_set('America/Lima'); ?>
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <div class="main-content">

            <!-- HEADER MINIMALISTA -->
            <header id="page-topbar">
                <div class="navbar-header">
                    <!-- LOGO MINIMALISTA -->
                    <div class="navbar-brand-box d-flex align-items-left">
                        <a href="<?php echo BASE_URL ?>" class="logo">
                            <i class="fas fa-graduation-cap"></i>
                            <span>
                                SIRE
                            </span>
                        </a>

                        <button type="button" class="btn btn-sm mr-2 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <!-- MENÚ SUPERIOR DERECHO - MINIMALISTA -->
                    <div class="d-flex align-items-center">
                        <!-- Selector de Institución -->
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect waves-light"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-university mr-1"></i>
                                <span class="d-none d-sm-inline-block ml-1" id="menu_ies">IES Huanta</span>
                                <i class="fas fa-chevron-down d-none d-sm-inline-block ml-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" id="contenido_menu_ies">
                                <!-- Opciones de institución -->
                            </div>
                        </div>

                        <!-- Perfil de Usuario -->
                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item waves-effect waves-light"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" alt="Usuario" width="32" height="32">
                                <span class="d-none d-sm-inline-block ml-1">Usuario</span>
                                <i class="fas fa-chevron-down d-none d-sm-inline-block ml-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                                </a>
                                <button class="dropdown-item" onclick="sent_email_password();">
                                    <i class="fas fa-key mr-2"></i>Cambiar Contraseña
                                </button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item text-danger" onclick="cerrar_sesion();">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- NAVEGACIÓN PRINCIPAL MINIMALISTA -->
            <div class="topnav">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">
                                <!-- INICIO -->
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL ?>">
                                        <i class="fas fa-home"></i>Inicio
                                    </a>
                                </li>

                                <!-- GESTIÓN -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cogs"></i>Gestión <i class="fas fa-chevron-down ml-1"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-components">
                                        <a href="<?php echo BASE_URL ?>usuarios" class="dropdown-item">
                                            <i class="fas fa-users mr-2"></i>Usuarios
                                        </a>
                                        <a href="<?php echo BASE_URL ?>estudiantes" class="dropdown-item">
                                            <i class="fas fa-user-graduate mr-2"></i>Estudiantes
                                        </a>
                                        <a href="<?php echo BASE_URL ?>programas" class="dropdown-item">
                                            <i class="fas fa-book mr-2"></i>Programas
                                        </a>
                                        <a href="<?php echo BASE_URL ?>semestres" class="dropdown-item">
                                            <i class="fas fa-calendar-alt mr-2"></i>Semestres
                                        </a>
                                        <a href="<?php echo BASE_URL ?>token" class="dropdown-item">
                                            <i class="fas fa-calendar-alt mr-2"></i>Tokens
                                        </a>
                                        <a href="<?php echo BASE_URL ?>clientes" class="dropdown-item">
                                            <i class="fas fa-calendar-alt mr-2"></i>Clientes
                                        <a href="<?php echo BASE_URL ?>apiestudiante" class="dropdown-item">
                                            <i class="fas fa-calendar-alt mr-2"></i>A P I
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- CONTENIDO PRINCIPAL -->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Popup de carga minimalista -->
                    <div id="popup-carga" style="display: none;">
                        <div class="popup-overlay">
                            <div class="popup-content">
                                <div class="spinner"></div>
                                <p>Cargando, por favor espere...</p>
                            </div>
                        </div>
                    </div>