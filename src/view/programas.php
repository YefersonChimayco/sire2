<!-- start page title -->
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Card de Título -->
        <div class="card">
            <div class="card-body">
                <div class="page-title-box d-flex align-items-center justify-content-between p-0">
                    <h4 class="mb-0 font-size-18">Programas de Estudio</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_registrar">
                            + Nuevo Programa
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
                        <h5 class="modal-title h4">Registrar Nuevo Programa</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="frmRegistrar">
                            <div class="form-group row mb-2">
                                <label for="nombre" class="col-3 col-form-label">Nombre *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="descripcion" class="col-3 col-form-label">Descripción</label>
                                <div class="col-9">
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group mb-0 justify-content-end row text-center">
                                <div class="col-12">
                                    <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrar_programa()">Registrar</button>
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
                        <label for="busqueda_tabla_nombre" class="col-5 col-form-label">Nombre:</label>
                        <div class="col-7">
                            <input type="text" class="form-control" name="busqueda_tabla_nombre" id="busqueda_tabla_nombre">
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
                    <input type="hidden" id="filtro_nombre" value="">
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

<script src="<?php echo BASE_URL; ?>src/view/js/functions_programa.js"></script>
<script>
    listar_programasOrdenados();
</script>
<!-- end page title -->