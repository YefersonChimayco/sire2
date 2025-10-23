<!-- start page title -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Card de Título -->
        <div class="card">
            <div class="card-body">
                <div class="page-title-box d-flex align-items-center justify-content-between p-0">
                    <h4 class="mb-0 font-size-18">Clientes API</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_registrar">
                            + Nuevo Cliente
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Registrar -->
        <div class="modal fade modal_registrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4">Registrar Nuevo Cliente</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="frmRegistrar">
                            <div class="form-group row mb-2">
                                <label for="ruc" class="col-3 col-form-label">RUC *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="ruc" name="ruc" required maxlength="11">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="razon_social" class="col-3 col-form-label">Razón Social *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="telefono" class="col-3 col-form-label">Teléfono</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="correo" class="col-3 col-form-label">Correo</label>
                                <div class="col-9">
                                    <input type="email" class="form-control" id="correo" name="correo">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="fecha_registro" class="col-3 col-form-label">Fecha Registro</label>
                                <div class="col-9">
                                    <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="estado" class="col-3 col-form-label">Estado</label>
                                <div class="col-9">
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-0 justify-content-end row text-center">
                                <div class="col-12">
                                    <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrar_cliente()">Registrar</button>
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
                <h4 class="card-title">Filtros de Búsqueda</h4>
                <div class="row col-12">
                    <div class="form-group row mb-3 col-6">
                        <label for="busqueda_tabla_ruc" class="col-5 col-form-label">RUC:</label>
                        <div class="col-7">
                            <input type="text" class="form-control" name="busqueda_tabla_ruc" id="busqueda_tabla_ruc">
                        </div>
                    </div>
                    <div class="form-group row mb-3 col-6">
                        <label for="busqueda_tabla_razon_social" class="col-5 col-form-label">Razón Social:</label>
                        <div class="col-7">
                            <input type="text" class="form-control" name="busqueda_tabla_razon_social" id="busqueda_tabla_razon_social">
                        </div>
                    </div>
                    <div class="form-group row mb-3 col-6">
                        <label for="busqueda_tabla_estado" class="col-5 col-form-label">Estado:</label>
                        <div class="col-7">
                            <select class="form-control" name="busqueda_tabla_estado" id="busqueda_tabla_estado">
                                <option value="">TODOS</option>
                                <option value="1">ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-center ">
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="numero_pagina(1);"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>

        <!-- Card de Resultados -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Resultados de Búsqueda</h4>
                <div id="filtros_tabla_header" class="form-group row page-title-box d-flex align-items-center justify-content-between m-0 mb-1 p-0">
                    <input type="hidden" id="pagina" value="1">
                    <input type="hidden" id="filtro_ruc" value="">
                    <input type="hidden" id="filtro_razon_social" value="">
                    <input type="hidden" id="filtro_estado" value="">
                    <div>
                        <label for="cantidad_mostrar">Mostrar</label>
                        <select name="cantidad_mostrar" id="cantidad_mostrar" class="form-control-sm" onchange="numero_pagina(1);">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="cantidad_mostrar">registros</label>
                    </div>
                </div>
                
                <!-- Tabla corregida -->
                <div id="tablas">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dt-responsive" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">Nro</th>
                                    <th width="15%">RUC</th>
                                    <th width="20%">Razón Social</th>
                                    <th width="12%">Teléfono</th>
                                    <th width="15%">Correo</th>
                                    <th width="12%">Fecha Registro</th>
                                    <th width="8%">Estado</th>
                                    <th width="13%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="contenido_tabla">
                                <!-- Las filas se cargarán dinámicamente aquí -->
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <div class="spinner-border spinner-border-sm mr-2" role="status"></div>
                                        Cargando clientes...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="filtros_tabla_footer" class="form-group row page-title-box d-flex align-items-center justify-content-between m-0 mb-1 p-0">
                    <div id="texto_paginacion_tabla" class="text-muted small">
                        Mostrando 0 de 0 registros
                    </div>
                    <div id="paginacion_tabla">
                        <ul class="pagination justify-content-end mb-0" id="lista_paginacion_tabla">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0)" tabindex="-1">Anterior</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0)">Siguiente</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="modals_editar"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>src/view/js/functions_cliente.js"></script>
<script>
    // Inicializar cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer valores por defecto
        document.getElementById('pagina').value = 1;
        document.getElementById('cantidad_mostrar').value = 10;
        
        // Cargar clientes
        listar_clientesOrdenados();
        
        // Event listeners para filtros
        document.getElementById('busqueda_tabla_ruc').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                numero_pagina(1);
            }
        });
        
        document.getElementById('busqueda_tabla_razon_social').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                numero_pagina(1);
            }
        });
    });
</script>
<!-- end page title -->