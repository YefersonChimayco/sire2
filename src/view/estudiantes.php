<!-- start page title -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Card de Título -->
        <div class="card">
            <div class="card-body">
                <div class="page-title-box d-flex align-items-center justify-content-between p-0">
                    <h4 class="mb-0 font-size-18">Estudiantes</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_registrar">
                            + Nuevo Estudiante
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Registrar (igual que usuarios) -->
        <div class="modal fade modal_registrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4">Registrar Nuevo Estudiante</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="frmRegistrar">
                            <div class="form-group row mb-2">
                                <label for="dni" class="col-3 col-form-label">DNI *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="dni" name="dni" required maxlength="8">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="nombres" class="col-3 col-form-label">Nombres *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="apellido_paterno" class="col-3 col-form-label">Apellido Paterno *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="apellido_materno" class="col-3 col-form-label">Apellido Materno</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="semestre" class="col-3 col-form-label">Semestre</label>
                                <div class="col-9">
                                    <select name="semestre" id="semestre" class="form-control">
                                        <option value="1">Primer Semestre</option>
                                        <option value="2">Segundo Semestre</option>
                                        <option value="3">Tercer Semestre</option>
                                        <option value="4">Cuarto Semestre</option>
                                        <option value="5">Quinto Semestre</option>
                                        <option value="6">Sexto Semestre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="programa_id" class="col-3 col-form-label">Programa</label>
                                <div class="col-9">
                                    <select name="programa_id" id="programa_id" class="form-control">
                                        <option value="1">Diseño y Programación Web</option>
                                        <option value="2">Enfermería Técnica</option>
                                        <option value="3">Mecánica Automotriz</option>
                                        <option value="4">Producción Agropecuaria</option>
                                        <option value="5">Industrias de Alimentos y Bebidas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="fecha_matricula" class="col-3 col-form-label">Fecha Matrícula</label>
                                <div class="col-9">
                                    <input type="date" class="form-control" id="fecha_matricula" name="fecha_matricula" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="estado" class="col-3 col-form-label">Estado</label>
                                <div class="col-9">
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                        <option value="graduado">Graduado</option>
                                        <option value="suspendido">Suspendido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-0 justify-content-end row text-center">
                                <div class="col-12">
                                    <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrar_estudiante()">Registrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Filtros (igual que usuarios) -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filtros de Búsqueda</h4>
                <div class="row col-12">
                    <div class="form-group row mb-3 col-6">
                        <label for="busqueda_tabla_dni" class="col-5 col-form-label">DNI:</label>
                        <div class="col-7">
                            <input type="text" class="form-control" name="busqueda_tabla_dni" id="busqueda_tabla_dni">
                        </div>
                    </div>
                    <div class="form-group row mb-3 col-6">
                        <label for="busqueda_tabla_nombres" class="col-5 col-form-label">Nombres/Apellidos:</label>
                        <div class="col-7">
                            <input type="text" class="form-control" name="busqueda_tabla_nombres" id="busqueda_tabla_nombres">
                        </div>
                    </div>
                    <div class="form-group row mb-3 col-6">
                        <label for="busqueda_tabla_estado" class="col-5 col-form-label">Estado:</label>
                        <div class="col-7">
                            <select class="form-control" name="busqueda_tabla_estado" id="busqueda_tabla_estado">
                                <option value="">TODOS</option>
                                <option value="activo">ACTIVO</option>
                                <option value="inactivo">INACTIVO</option>
                                <option value="graduado">GRADUADO</option>
                                <option value="suspendido">SUSPENDIDO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-center ">
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="numero_pagina(1);"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>

        <!-- Card de Resultados (igual que usuarios) -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Resultados de Búsqueda</h4>
                <div id="filtros_tabla_header" class="form-group row page-title-box d-flex align-items-center justify-content-between m-0 mb-1 p-0">
                    <input type="hidden" id="pagina" value="1">
                    <input type="hidden" id="filtro_dni" value="">
                    <input type="hidden" id="filtro_nombres" value="">
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
                <div id="tablas"></div>
                <div id="filtros_tabla_footer" class="form-group row page-title-box d-flex align-items-center justify-content-between m-0 mb-1 p-0">
                    <div id="texto_paginacion_tabla"></div>
                    <div id="paginacion_tabla">
                        <ul class="pagination justify-content-end" id="lista_paginacion_tabla"></ul>
                    </div>
                </div>
                <div id="modals_editar"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>src/view/js/functions_estudiante.js"></script>
<script>
    listar_estudiantesOrdenados();
</script>
<!-- end page title -->