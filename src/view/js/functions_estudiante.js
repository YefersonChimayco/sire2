// functions_estudiante.js - IDÉNTICO AL DE USUARIOS

function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_estudiantesOrdenados();
}

async function listar_estudiantesOrdenados() {
    try {
        mostrarPopupCarga();
        
        // Para filtro
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_dni = document.getElementById('busqueda_tabla_dni').value;
        let busqueda_tabla_nombres = document.getElementById('busqueda_tabla_nombres').value;
        let busqueda_tabla_estado = document.getElementById('busqueda_tabla_estado').value;
        
        // Asignamos valores para guardar
        document.getElementById('filtro_dni').value = busqueda_tabla_dni;
        document.getElementById('filtro_nombres').value = busqueda_tabla_nombres;
        document.getElementById('filtro_estado').value = busqueda_tabla_estado;

        // Generamos el formulario
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tabla_dni', busqueda_tabla_dni);
        formData.append('busqueda_tabla_nombres', busqueda_tabla_nombres);
        formData.append('busqueda_tabla_estado', busqueda_tabla_estado);
        formData.append('sesion', session_session);
        formData.append('token', token_token);
        
        // Enviar datos hacia el controlador
        let respuesta = await fetch(base_url_server + 'src/control/EstudianteController.php?tipo=listar_estudiantes_ordenados_tabla', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        let json = await respuesta.json();
        
        // Limpiar tabla
        document.getElementById('tablas').innerHTML = `<table id="" class="table dt-responsive" width="100%">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>DNI</th>
                            <th>Nombres Completos</th>
                            <th>Semestre</th>
                            <th>Programa</th>
                            <th>Estado</th>
                            <th>Fecha Matrícula</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="contenido_tabla">
                    </tbody>
                </table>`;
        document.querySelector('#modals_editar').innerHTML = ``;
        
        if (json.status) {
            let datos = json.contenido;
            datos.forEach(item => {
                generarfilastabla(item);
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            document.getElementById('tablas').innerHTML = `<div class="alert alert-info">No se encontraron estudiantes</div>`;
        }
        
        let paginacion = generar_paginacion(json.total, cantidad_mostrar);
        let texto_paginacion = generar_texto_paginacion(json.total, cantidad_mostrar);
        document.getElementById('texto_paginacion_tabla').innerHTML = texto_paginacion;
        document.getElementById('lista_paginacion_tabla').innerHTML = paginacion;
        
    } catch (e) {
        console.log("Error al cargar estudiantes: " + e);
        document.getElementById('tablas').innerHTML = `<div class="alert alert-danger">Error al cargar estudiantes</div>`;
    } finally {
        ocultarPopupCarga();
    }
}

function generarfilastabla(item) {
    let cont = 1;
    $(".filas_tabla").each(function () {
        cont++;
    })
    
    let nueva_fila = document.createElement("tr");
    nueva_fila.id = "fila" + item.dni;
    nueva_fila.className = "filas_tabla";

    // Formatear fecha
    let fecha_matricula = item.fecha_matricula ? new Date(item.fecha_matricula).toLocaleDateString('es-ES') : 'N/A';
    
    // Badge de estado
    let estado_badge = '';
    switch(item.estado) {
        case 'activo':
            estado_badge = '<span class="badge badge-success">ACTIVO</span>';
            break;
        case 'inactivo':
            estado_badge = '<span class="badge badge-danger">INACTIVO</span>';
            break;
        case 'graduado':
            estado_badge = '<span class="badge badge-info">GRADUADO</span>';
            break;
        case 'suspendido':
            estado_badge = '<span class="badge badge-warning">SUSPENDIDO</span>';
            break;
        default:
            estado_badge = '<span class="badge badge-secondary">' + item.estado + '</span>';
    }

    nueva_fila.innerHTML = `
        <th>${cont}</th>
        <td><strong>${item.dni}</strong></td>
        <td>${item.apellido_paterno} ${item.apellido_materno}, ${item.nombres}</td>
        <td>Semestre ${item.semestre}</td>
    <td>${item.programa_nombre || 'No asignado'}</td>
        <td>${estado_badge}</td>
        <td>${fecha_matricula}</td>
        <td>${item.options}</td>
    `;
    
    // Modal de edición
    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.dni}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4">Actualizar datos de estudiante</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.dni}">
                                <div class="form-group row mb-2">
                                    <label for="dni${item.dni}" class="col-3 col-form-label">DNI</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="dni${item.dni}" name="dni" value="${item.dni}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="nombres${item.dni}" class="col-3 col-form-label">Nombres</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="nombres${item.dni}" name="nombres" value="${item.nombres}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="apellido_paterno${item.dni}" class="col-3 col-form-label">Apellido Paterno</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="apellido_paterno${item.dni}" name="apellido_paterno" value="${item.apellido_paterno}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="apellido_materno${item.dni}" class="col-3 col-form-label">Apellido Materno</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="apellido_materno${item.dni}" name="apellido_materno" value="${item.apellido_materno}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="semestre${item.dni}" class="col-3 col-form-label">Semestre</label>
                                    <div class="col-9">
                                        <select name="semestre" id="semestre${item.dni}" class="form-control">
                                            <option value="1" ${item.semestre == 1 ? 'selected' : ''}>Primer Semestre</option>
                                            <option value="2" ${item.semestre == 2 ? 'selected' : ''}>Segundo Semestre</option>
                                            <option value="3" ${item.semestre == 3 ? 'selected' : ''}>Tercer Semestre</option>
                                            <option value="4" ${item.semestre == 4 ? 'selected' : ''}>Cuarto Semestre</option>
                                            <option value="5" ${item.semestre == 5 ? 'selected' : ''}>Quinto Semestre</option>
                                            <option value="6" ${item.semestre == 6 ? 'selected' : ''}>Sexto Semestre</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="programa_id${item.dni}" class="col-3 col-form-label">Programa</label>
                                    <div class="col-9">
                                        <select name="programa_id" id="programa_id${item.dni}" class="form-control">
                                            <option value="1" ${item.programa_id == 1 ? 'selected' : ''}>Diseño y Programación Web</option>
                                            <option value="2" ${item.programa_id == 2 ? 'selected' : ''}>Enfermería Técnica</option>
                                            <option value="3" ${item.programa_id == 3 ? 'selected' : ''}>Mecánica Automotriz</option>
                                            <option value="4" ${item.programa_id == 4 ? 'selected' : ''}>Producción Agropecuaria</option>
                                            <option value="5" ${item.programa_id == 5 ? 'selected' : ''}>Industrias de Alimentos y Bebidas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="fecha_matricula${item.dni}" class="col-3 col-form-label">Fecha Matrícula</label>
                                    <div class="col-9">
                                        <input type="date" class="form-control" id="fecha_matricula${item.dni}" name="fecha_matricula" value="${item.fecha_matricula}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="estado${item.dni}" class="col-3 col-form-label">Estado</label>
                                    <div class="col-9">
                                        <select name="estado" id="estado${item.dni}" class="form-control">
                                            <option value="activo" ${item.estado == 'activo' ? 'selected' : ''}>Activo</option>
                                            <option value="inactivo" ${item.estado == 'inactivo' ? 'selected' : ''}>Inactivo</option>
                                            <option value="graduado" ${item.estado == 'graduado' ? 'selected' : ''}>Graduado</option>
                                            <option value="suspendido" ${item.estado == 'suspendido' ? 'selected' : ''}>Suspendido</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarEstudiante('${item.dni}')">Actualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    document.querySelector('#contenido_tabla').appendChild(nueva_fila);
}

async function registrar_estudiante() {
    let dni = document.getElementById('dni').value;
    let nombres = document.querySelector('#nombres').value;
    let apellido_paterno = document.querySelector('#apellido_paterno').value;
    
    if (dni == "" || nombres == "" || apellido_paterno == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos obligatorios vacíos',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
        return;
    }
    
    try {
        // Capturamos datos del formulario html
        const datos = new FormData(frmRegistrar);
        datos.append('sesion', session_session);
        datos.append('token', token_token);
        
        // Enviar datos hacia el controlador
        let respuesta = await fetch(base_url_server + 'src/control/EstudianteController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        
        json = await respuesta.json();
        
        if (json.status) {
            document.getElementById("frmRegistrar").reset();
            Swal.fire({
                type: 'success',
                title: 'Registro',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            $('.modal_registrar').modal('hide');
            listar_estudiantesOrdenados();
            
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            })
        }
    } catch (e) {
        console.log("Oops, ocurrio un error " + e);
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Error al registrar estudiante',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
    }
}

async function actualizarEstudiante(dni) {
    let dni_nuevo = document.getElementById('dni' + dni).value;
    let nombres = document.querySelector('#nombres' + dni).value;
    let apellido_paterno = document.querySelector('#apellido_paterno' + dni).value;
    
    if (dni_nuevo == "" || nombres == "" || apellido_paterno == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos obligatorios vacíos',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: '',
            timer: 1000
        })
        return;
    }
    
    const formulario = document.getElementById('frmActualizar' + dni);
    const datos = new FormData(formulario);
    datos.append('data', dni); // DNI original
    datos.append('sesion', session_session);
    datos.append('token', token_token);
    
    try {
        let respuesta = await fetch(base_url_server + 'src/control/EstudianteController.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        
        json = await respuesta.json();
        
        if (json.status) {
            $('.modal_editar' + dni).modal('hide');
            Swal.fire({
                type: 'success',
                title: 'Actualizar',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            listar_estudiantesOrdenados();
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            })
        }
    } catch (e) {
        console.log("Error al actualizar estudiante: " + e);
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Error al actualizar estudiante',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
    }
}

function eliminar_estudiante(dni) {
    Swal.fire({
        title: "¿Estás seguro de eliminar este estudiante?",
        text: "Esta acción no se puede deshacer",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.value) {
            eliminarEstudianteConfirmado(dni);
        }
    });
}

async function eliminarEstudianteConfirmado(dni) {
    try {
        const formData = new FormData();
        formData.append('dni', dni);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/EstudianteController.php?tipo=eliminar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });
        
        json = await respuesta.json();
        
        if (json.status) {
            Swal.fire({
                type: 'success',
                title: 'Eliminado',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            listar_estudiantesOrdenados();
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            })
        }
    } catch (e) {
        console.log("Error al eliminar estudiante: " + e);
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Error al eliminar estudiante',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
    }
}