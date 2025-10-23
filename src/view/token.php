<?php
// token.php - Vista completa de gestión de tokens
?>

<!-- start page title -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Card de Título -->
        <div class="card">
            <div class="card-body">
                <div class="page-title-box d-flex align-items-center justify-content-between p-0">
                    <h4 class="mb-0 font-size-18">Tokens API</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_registrar" id="btnNuevoToken">
                            <i class="fa fa-plus mr-1"></i> Nuevo Token
                        </button>
                    </div>
                </div>
                <p class="text-muted mb-0">Gestión segura de tokens de acceso API</p>
            </div>
        </div>

        <!-- Modal Registrar -->
        <div class="modal fade modal_registrar" tabindex="-1" role="dialog" aria-labelledby="modalRegistrarLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title h4" id="modalRegistrarLabel">
                            <i class="fa fa-key mr-2"></i>Generar Nuevo Token
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="frmRegistrar" onsubmit="return false;">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle mr-2"></i>
                                Al guardar se generará automáticamente un token seguro de 64 caracteres.
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label for="id_client_api" class="col-md-4 col-form-label">
                                    Cliente API <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-8">
                                    <select name="id_client_api" id="id_client_api" class="form-control" required>
                                        <option value="">Seleccionar cliente...</option>
                                        <!-- Los clientes se cargarán via JavaScript/AJAX -->
                                    </select>
                                    <small class="form-text text-muted">Cliente que utilizará este token</small>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label for="fecha_reg" class="col-md-4 col-form-label">Fecha Registro</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control" id="fecha_reg" name="fecha_reg" 
                                           value="<?php echo htmlspecialchars(date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>"
                                           max="<?php echo htmlspecialchars(date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>">
                                    <small class="form-text text-muted">Fecha de creación del token</small>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3">
                                <label for="estado" class="col-md-4 col-form-label">Estado</label>
                                <div class="col-md-8">
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                    <small class="form-text text-muted">Estado inicial del token</small>
                                </div>
                            </div>
                            
                            <div class="form-group mb-0 justify-content-end row">
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-light waves-effect waves-light mr-2" data-dismiss="modal">
                                        <i class="fa fa-times mr-1"></i> Cancelar
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrar_token()" id="btnRegistrar">
                                        <i class="fa fa-save mr-1"></i> Generar Token
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Filtros -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">
                    <i class="fa fa-filter mr-2"></i>Filtros de Búsqueda
                </h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="busqueda_tabla_cliente" class="form-label">Cliente:</label>
                            <select class="form-control" name="busqueda_tabla_cliente" id="busqueda_tabla_cliente">
                                <option value="">TODOS LOS CLIENTES</option>
                                <!-- Los clientes se cargarán via JavaScript/AJAX -->
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="busqueda_tabla_estado" class="form-label">Estado:</label>
                            <select class="form-control" name="busqueda_tabla_estado" id="busqueda_tabla_estado">
                                <option value="">TODOS LOS ESTADOS</option>
                                <option value="1">ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary waves-effect waves-light" onclick="numero_pagina(1)">
                            <i class="fa fa-search mr-1"></i> Buscar Tokens
                        </button>
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light ml-2" onclick="resetFilters()">
                            <i class="fa fa-refresh mr-1"></i> Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Resultados -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-list mr-2"></i>Resultados de Búsqueda
                    </h4>
                    <div class="badge badge-info" id="contadorTokens">
                        Cargando...
                    </div>
                </div>
                
                <!-- Controles de paginación superior -->
                <div id="filtros_tabla_header" class="form-group row align-items-center justify-content-between m-0 mb-3 p-0">
                    <input type="hidden" id="pagina" value="1">
                    <input type="hidden" id="filtro_cliente" value="">
                    <input type="hidden" id="filtro_estado" value="">
                    
                    <div class="d-flex align-items-center">
                        <label for="cantidad_mostrar" class="mb-0 mr-2">Mostrar</label>
                        <select name="cantidad_mostrar" id="cantidad_mostrar" class="form-control form-control-sm" style="width: auto;" 
                                onchange="numero_pagina(1)">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="cantidad_mostrar" class="mb-0 ml-2">registros</label>
                    </div>
                </div>

                <!-- Tabla de resultados -->
                <div class="table-responsive">
                    <div id="tablas">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando tokens...</span>
                            </div>
                            <p class="mt-2 text-muted">Cargando tokens...</p>
                        </div>
                    </div>
                </div>

                <!-- Controles de paginación inferior -->
                <div id="filtros_tabla_footer" class="form-group row align-items-center justify-content-between m-0 mt-3 p-0">
                    <div id="texto_paginacion_tabla" class="text-muted">
                        Cargando información...
                    </div>
                    <div id="paginacion_tabla">
                        <ul class="pagination justify-content-end mb-0" id="lista_paginacion_tabla">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0)">Cargando...</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contenedor para modales de edición -->
                <div id="modals_editar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?php echo BASE_URL; ?>src/view/js/functions_token.js"></script>
<script>
// Inicialización cuando el documento está listo
document.addEventListener('DOMContentLoaded', function() {
    // Cargar tokens automáticamente al cargar la página
    setTimeout(() => {
        listar_tokensOrdenados();
        cargarClientes(); // Cargar clientes via AJAX
    }, 500);
    
    // Event listeners para los filtros
    document.getElementById('busqueda_tabla_cliente').addEventListener('change', function() {
        numero_pagina(1);
    });
    
    document.getElementById('busqueda_tabla_estado').addEventListener('change', function() {
        numero_pagina(1);
    });
    
    // Limpiar formulario cuando se cierre el modal de registro
    $('.modal_registrar').on('hidden.bs.modal', function () {
        document.getElementById('frmRegistrar').reset();
        document.getElementById('fecha_reg').value = '<?php echo date('Y-m-d'); ?>';
    });
});

// Función para cargar clientes via AJAX
async function cargarClientes() {
    try {
        const formData = new FormData();
        formData.append('sesion', session_session);
        formData.append('token', token_token);
        
        const response = await fetch(`${base_url_server}src/control/TokenController.php?tipo=get_clientes`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status && data.clientes) {
            // Llenar select de registro
            const selectRegistro = document.getElementById('id_client_api');
            const selectFiltro = document.getElementById('busqueda_tabla_cliente');
            
            selectRegistro.innerHTML = '<option value="">Seleccionar cliente...</option>';
            selectFiltro.innerHTML = '<option value="">TODOS LOS CLIENTES</option>';
            
            data.clientes.forEach(cliente => {
                selectRegistro.innerHTML += `<option value="${cliente.id}">${cliente.razon_social}</option>`;
                selectFiltro.innerHTML += `<option value="${cliente.id}">${cliente.razon_social}</option>`;
            });
        }
    } catch (error) {
        console.error('Error cargando clientes:', error);
    }
}

// Función para resetear filtros
function resetFilters() {
    document.getElementById('busqueda_tabla_cliente').value = '';
    document.getElementById('busqueda_tabla_estado').value = '';
    numero_pagina(1);
}

// Función global para compatibilidad
function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_tokensOrdenados();
}
</script>