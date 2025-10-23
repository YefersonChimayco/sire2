<div class="row">
    <!-- Tarjeta de Usuarios -->
    <div class="col-md-6 col-xl-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-info text-right">
                        <span class="text-muted small">Total</span>
                    </div>
                </div>
                <h5 class="card-title text-muted mb-2">Usuarios</h5>
                <h2 class="card-value mb-3">2</h2>
                <a href="<?php echo BASE_URL ?>usuarios" class="btn btn-outline-primary btn-sm btn-block">
                    <i class="fas fa-eye mr-1"></i>Ver Detalles
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Estudiantes -->
    <div class="col-md-6 col-xl-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="card-info text-right">
                        <span class="text-muted small">Total</span>
                    </div>
                </div>
                <h5 class="card-title text-muted mb-2">Estudiantes</h5>
                <h2 class="card-value mb-3">1,248</h2>
                <a href="<?php echo BASE_URL ?>estudiantes" class="btn btn-outline-primary btn-sm btn-block">
                    <i class="fas fa-eye mr-1"></i>Ver Detalles
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Programas -->
    <div class="col-md-6 col-xl-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="card-info text-right">
                        <span class="text-muted small">Total</span>
                    </div>
                </div>
                <h5 class="card-title text-muted mb-2">Programas de Estudio</h5>
                <h2 class="card-value mb-3">5</h2>
                <a href="<?php echo BASE_URL ?>programas" class="btn btn-outline-primary btn-sm btn-block">
                    <i class="fas fa-eye mr-1"></i>Ver Detalles
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Semestres -->
    <div class="col-md-6 col-xl-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="card-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-info text-right">
                        <span class="text-muted small">Total</span>
                    </div>
                </div>
                <h5 class="card-title text-muted mb-2">Semestres</h5>
                <h2 class="card-value mb-3">6</h2>
                <a href="<?php echo BASE_URL ?>semestres" class="btn btn-outline-primary btn-sm btn-block">
                    <i class="fas fa-eye mr-1"></i>Ver Detalles
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Accesos Rápidos -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Accesos Rápidos</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>usuarios" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h6>Gestión de Usuarios</h6>
                                <small class="text-muted">Administrar usuarios del sistema</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>estudiantes" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                <h6>Registro Estudiantil</h6>
                                <small class="text-muted">Gestionar estudiantes</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>programas" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <h6>Programas Académicos</h6>
                                <small class="text-muted">Administrar programas</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>semestres" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <h6>Periodos Académicos</h6>
                                <small class="text-muted">Gestionar semestres</small>
                            </div>
                        </a>
                    </div>
                       <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>token" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <h6>Periodos Académicos</h6>
                                <small class="text-muted">Gestionar tokens</small>
                            </div>
                        </a>
                    </div>
                       <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>clientes" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <h6>Periodos Académicos</h6>
                                <small class="text-muted">Gestionar Clientes</small>
                            </div>
                        </a>
                    </div>
                       <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo BASE_URL ?>apiestudiante" class="quick-access-link">
                            <div class="quick-access-item text-center p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <h6>Periodos Académicos</h6>
                                <small class="text-muted">api</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== ESTILOS PARA DASHBOARD MINIMALISTA ===== */
    .dashboard-card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: all 0.3s ease;
        background: var(--text-light);
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: var(--secondary-color);
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: rgba(44, 90, 160, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary-color);
        font-size: 1.2rem;
    }

    .card-title {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-value {
        font-size: 2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }

    .btn-outline-primary {
        border: 1px solid var(--secondary-color);
        color: var(--secondary-color);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-1px);
    }

    /* ===== ESTILOS PARA ACCESOS RÁPIDOS ===== */
    .quick-access-link {
        text-decoration: none;
        color: inherit;
    }

    .quick-access-item {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: all 0.3s ease;
        background: var(--text-light);
    }

    .quick-access-item:hover {
        border-color: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .quick-access-item i {
        color: var(--secondary-color);
        transition: all 0.3s ease;
    }

    .quick-access-item:hover i {
        transform: scale(1.1);
    }

    .quick-access-item h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .quick-access-item small {
        font-size: 0.8rem;
        line-height: 1.3;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .card-value {
            font-size: 1.5rem;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .quick-access-item {
            padding: 1.5rem !important;
        }
        
        .quick-access-item i {
            font-size: 1.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .col-md-6 {
            margin-bottom: 1rem;
        }
        
        .dashboard-card {
            margin-bottom: 1rem;
        }
    }
</style>