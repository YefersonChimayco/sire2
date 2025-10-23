<?php
/**
 * apiestudiante.php - Vista para API de Estudiantes
 * Versión minimalista con solo dependencias necesarias
 */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>API Estudiantes - SIRE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Solo dependencias esenciales -->
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    
    <style>
        /* Estilos mínimos necesarios */
        body {
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>

    <script>
        const base_url = '<?php echo BASE_URL; ?>';
        const base_url_server = '<?php echo BASE_URL_SERVER; ?>';
        <?php if(isset($_SESSION['sesion_id'])): ?>
        const session_session = '<?php echo $_SESSION['sesion_id']; ?>';
        const token_token = '<?php echo $_SESSION['sesion_token']; ?>';
        <?php else: ?>
        const session_session = '';
        const token_token = '';
        <?php endif; ?>
    </script>
</head>

<body>

    <div class="container-fluid">

        <!-- Card de Información -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="fas fa-api mr-2"></i>API de Estudiantes
                </h4>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Token automático:</strong> El sistema usa autenticación automática. 
                    Puede comenzar a buscar estudiantes inmediatamente.
                </div>
            </div>
        </div>

        <!-- Card de Búsqueda -->
        <div class="card" id="busquedaSection">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="fas fa-search mr-2"></i>Búsqueda de Estudiantes
                </h4>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" class="form-control" name="dni" id="dni" 
                                   placeholder="Ej: 41664487" maxlength="8"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <small class="form-text text-muted">Solo números, 8 dígitos</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nombres" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombres" id="nombres" 
                                   placeholder="Ej: Jesus Ordoñez">
                            <small class="form-text text-muted">Nombre, apellido paterno o materno</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="limite" class="form-label">Mostrar:</label>
                            <select class="form-control" name="limite" id="limite">
                                <option value="10">10</option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Use DNI para búsqueda exacta o Nombre para búsqueda general
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0 text-center">
                    <button type="button" class="btn btn-primary waves-effect waves-light mr-2" onclick="ejecutarBusqueda()">
                        <i class="fas fa-search mr-1"></i> Buscar Estudiantes
                    </button>
                    <button type="button" class="btn btn-success waves-effect waves-light mr-2" onclick="buscarPorDNI()">
                        <i class="fas fa-id-card mr-1"></i> Buscar por DNI
                    </button>
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light" onclick="limpiarFiltros()">
                        <i class="fas fa-refresh mr-1"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Card de Resultados -->
        <div class="card" id="resultadosSection">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-list mr-2"></i>Resultados de Búsqueda
                    </h4>
                    <div class="badge badge-secondary" id="contadorResultados">
                        Esperando búsqueda...
                    </div>
                </div>
                
                <!-- Controles de paginación superior -->
                <div id="filtros_tabla_header" class="form-group row align-items-center justify-content-between m-0 mb-3 p-0">
                    <input type="hidden" id="pagina" value="1">
                    
                    <div class="d-flex align-items-center">
                        <label for="limite" class="mb-0 mr-2">Mostrar</label>
                        <select name="limite" id="limite" class="form-control form-control-sm" style="width: auto;" 
                                onchange="document.getElementById('pagina').value=1; ejecutarBusqueda();">
                            <option value="10">10</option>
                            <option value="20" selected>20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="limite" class="mb-0 ml-2">registros</label>
                    </div>
                </div>

                <!-- Loading -->
                <div class="loading-spinner" id="loadingSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Buscando estudiantes...</p>
                </div>

                <!-- Controles de exportación -->
                <div class="d-flex justify-content-between align-items-center mb-3" id="controlesResultados" style="display: none;">
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm mr-2" onclick="exportarJSON()" id="btnExportar">
                            <i class="fas fa-download mr-1"></i> Exportar JSON
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="mostrarJSON()" id="btnVerJSON">
                            <i class="fas fa-code mr-1"></i> Ver JSON
                        </button>
                    </div>
                    <div class="text-muted small" id="infoPaginacion">-</div>
                </div>

                <!-- Tabla de resultados -->
                <div class="table-responsive">
                    <table class="table table-hover" width="100%" id="tablaResultados">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombre Completo</th>
                                <th>Programa</th>
                                <th>Semestre</th>
                                <th>Estado</th>
                                <th>Matrícula</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTabla">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-search fa-3x mb-3"></i>
                                    <h5>Realice una búsqueda para ver los resultados</h5>
                                    <p class="text-muted">Use los filtros arriba para buscar estudiantes</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación inferior -->
                <div id="filtros_tabla_footer" class="form-group row align-items-center justify-content-between m-0 mt-3 p-0">
                    <div id="texto_paginacion_tabla" class="text-muted">
                        Ingrese criterios de búsqueda
                    </div>
                    <div id="paginacion_tabla">
                        <ul class="pagination justify-content-end mb-0" id="lista_paginacion_tabla">
                            <li class="page-item disabled">
                                <span class="page-link">-</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Solo scripts esenciales -->
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/jquery.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.js"></script>
    
    <!-- Script de la API -->
    <script src="<?php echo BASE_URL ?>src/view/js/functions_api.js"></script>

    <script>
    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ API Estudiantes cargada - Versión minimalista');
    });
    </script>

</body>
</html>