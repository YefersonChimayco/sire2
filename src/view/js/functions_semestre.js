// functions_semestre.js - IDÉNTICO AL DE PROGRAMAS

function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_semestresOrdenados();
}

async function listar_semestresOrdenados() {
    try {
        mostrarPopupCarga();
        
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_descripcion = document.getElementById('busqueda_tabla_descripcion').value;
        
        document.getElementById('filtro_descripcion').value = busqueda_tabla_descripcion;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tabla_descripcion', busqueda_tabla_descripcion);
        formData.append('sesion', session_session);
        formData.append('token', token_token);
        
        let respuesta = await fetch(base_url_server + 'src/control/SemestreController.php?tipo=listar_semestres_ordenados_tabla', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        let json = await respuesta.json();
        
        document.getElementById('tablas').innerHTML = `<table id="" class="table dt-responsive" width="100%">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Descripción</th>
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
            document.getElementById('tablas').innerHTML = `<div class="alert alert-info">No se encontraron semestres</div>`;
        }
        
        let paginacion = generar_paginacion(json.total, cantidad_mostrar);
        let texto_paginacion = generar_texto_paginacion(json.total, cantidad_mostrar);
        document.getElementById('texto_paginacion_tabla').innerHTML = texto_paginacion;
        document.getElementById('lista_paginacion_tabla').innerHTML = paginacion;
        
    } catch (e) {
        console.log("Error al cargar semestres: " + e);
        document.getElementById('tablas').innerHTML = `<div class="alert alert-danger">Error al cargar semestres</div>`;
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
    nueva_fila.id = "fila" + item.id;
    nueva_fila.className = "filas_tabla";

    nueva_fila.innerHTML = `
        <th>${cont}</th>
        <td><strong>${item.descripcion}</strong></td>
        <td>${item.options}</td>
    `;
    
    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.id}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4">Actualizar Semestre</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="descripcion${item.id}" class="col-3 col-form-label">Descripción *</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="descripcion${item.id}" name="descripcion" value="${item.descripcion}" required>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarSemestre(${item.id})">Actualizar</button>
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

async function registrar_semestre() {
    let descripcion = document.getElementById('descripcion').value;
    
    if (descripcion == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Descripción es obligatoria',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
        return;
    }
    
    try {
        const datos = new FormData(frmRegistrar);
        datos.append('sesion', session_session);
        datos.append('token', token_token);
        
        let respuesta = await fetch(base_url_server + 'src/control/SemestreController.php?tipo=registrar', {
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
            listar_semestresOrdenados();
            
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
        console.log("Error al registrar semestre: " + e);
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Error al registrar semestre',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
    }
}

async function actualizarSemestre(id) {
    let descripcion = document.getElementById('descripcion' + id).value;
    
    if (descripcion == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Descripción es obligatoria',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: '',
            timer: 1000
        })
        return;
    }
    
    const formulario = document.getElementById('frmActualizar' + id);
    const datos = new FormData(formulario);
    datos.append('data', id);
    datos.append('sesion', session_session);
    datos.append('token', token_token);
    
    try {
        let respuesta = await fetch(base_url_server + 'src/control/SemestreController.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        
        json = await respuesta.json();
        
        if (json.status) {
            $('.modal_editar' + id).modal('hide');
            Swal.fire({
                type: 'success',
                title: 'Actualizar',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            listar_semestresOrdenados();
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
        console.log("Error al actualizar semestre: " + e);
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Error al actualizar semestre',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
    }
}

function eliminar_semestre(id) {
    Swal.fire({
        title: "¿Estás seguro de eliminar este semestre?",
        text: "Esta acción no se puede deshacer",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.value) {
            eliminarSemestreConfirmado(id);
        }
    });
}

async function eliminarSemestreConfirmado(id) {
    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/SemestreController.php?tipo=eliminar', {
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
            listar_semestresOrdenados();
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
        console.log("Error al eliminar semestre: " + e);
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Error al eliminar semestre',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        })
    }
}