// Variables globales
let currentPage = 1;
const itemsPerPage = 10;

// Función para mostrar/ocultar carga
function mostrarPopupCarga() {
    // Implementar según tu framework
    console.log("Mostrando carga...");
}

function ocultarPopupCarga() {
    // Implementar según tu framework
    console.log("Ocultando carga...");
}

// Función para cambiar página
function numero_pagina(pagina) {
    currentPage = pagina;
    document.getElementById('pagina').value = pagina;
    listar_clientesOrdenados();
}

// Listar clientes
async function listar_clientesOrdenados() {
    try {
        mostrarPopupCarga();
        
        // Obtener valores de filtros
        let pagina = document.getElementById('pagina').value || currentPage;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value || itemsPerPage;
        let busqueda_tabla_ruc = document.getElementById('busqueda_tabla_ruc').value || '';
        let busqueda_tabla_razon_social = document.getElementById('busqueda_tabla_razon_social').value || '';
        let busqueda_tabla_estado = document.getElementById('busqueda_tabla_estado').value || '';

        // Preparar datos del formulario
        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tabla_ruc', busqueda_tabla_ruc);
        formData.append('busqueda_tabla_razon_social', busqueda_tabla_razon_social);
        formData.append('busqueda_tabla_estado', busqueda_tabla_estado);
        formData.append('sesion', session_session || '');
        formData.append('token', token_token || '');

        // Realizar petición
        let respuesta = await fetch(base_url_server + 'src/control/ClienteController.php?tipo=listar_clientes_ordenados_tabla', {
            method: 'POST',
            body: formData
        });

        let json = await respuesta.json();
        
        // Limpiar tabla y modals
        document.getElementById('contenido_tabla').innerHTML = '';
        document.querySelector('#modals_editar').innerHTML = '';
        
        if (json.status) {
            let datos = json.contenido;
            
            if (datos.length > 0) {
                datos.forEach(item => {
                    generarFilaTabla(item);
                });
            } else {
                document.getElementById('contenido_tabla').innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center">No se encontraron clientes</td>
                    </tr>`;
            }
            
            // Actualizar paginación
            actualizarPaginacion(json.total, cantidad_mostrar);
            
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            document.getElementById('contenido_tabla').innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">Error al cargar clientes: ${json.mensaje || 'Error desconocido'}</td>
                </tr>`;
        }
        
    } catch (error) {
        console.error("Error al cargar clientes:", error);
        document.getElementById('contenido_tabla').innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-danger">Error de conexión: ${error.message}</td>
            </tr>`;
    } finally {
        ocultarPopupCarga();
    }
}

// Generar fila de tabla
function generarFilaTabla(item) {
    const tbody = document.getElementById('contenido_tabla');
    
    // Formatear fecha
    let fechaFormateada = 'N/A';
    if (item.fecha_registro && item.fecha_registro !== 'N/A') {
        try {
            const fecha = new Date(item.fecha_registro);
            fechaFormateada = fecha.toLocaleDateString('es-ES');
        } catch (e) {
            fechaFormateada = item.fecha_registro;
        }
    }
    
    // Badge de estado
    const estadoBadge = item.estado == '1' ? 
        '<span class="badge badge-success">ACTIVO</span>' : 
        '<span class="badge badge-danger">INACTIVO</span>';

    const fila = document.createElement('tr');
    fila.className = 'filas_tabla';
    fila.innerHTML = `
        <th>${item.nro}</th>
        <td><strong>${item.ruc}</strong></td>
        <td>${item.razon_social}</td>
        <td>${item.telefono}</td>
        <td>${item.correo}</td>
        <td>${fechaFormateada}</td>
        <td>${estadoBadge}</td>
        <td>${item.opciones}</td>
    `;
    
    tbody.appendChild(fila);
    
    // Generar modal de edición
    generarModalEdicion(item);
}

// Generar modal de edición
function generarModalEdicion(item) {
    const modalsContainer = document.querySelector('#modals_editar');
    
    const modal = document.createElement('div');
    modal.className = `modal fade modal_editar_${item.id}`;
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title h4">Actualizar datos de cliente</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <form class="form-horizontal" id="frmActualizar_${item.id}">
                            <div class="form-group row mb-2">
                                <label for="ruc_${item.id}" class="col-3 col-form-label">RUC *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="ruc_${item.id}" name="ruc" value="${item.ruc}" required maxlength="11">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="razon_social_${item.id}" class="col-3 col-form-label">Razón Social *</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="razon_social_${item.id}" name="razon_social" value="${item.razon_social}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="telefono_${item.id}" class="col-3 col-form-label">Teléfono</label>
                                <div class="col-9">
                                    <input type="text" class="form-control" id="telefono_${item.id}" name="telefono" value="${item.telefono}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="correo_${item.id}" class="col-3 col-form-label">Correo</label>
                                <div class="col-9">
                                    <input type="email" class="form-control" id="correo_${item.id}" name="correo" value="${item.correo}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="fecha_registro_${item.id}" class="col-3 col-form-label">Fecha Registro</label>
                                <div class="col-9">
                                    <input type="date" class="form-control" id="fecha_registro_${item.id}" name="fecha_registro" value="${item.fecha_registro}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="estado_${item.id}" class="col-3 col-form-label">Estado</label>
                                <div class="col-9">
                                    <select name="estado" id="estado_${item.id}" class="form-control">
                                        <option value="1" ${item.estado == '1' ? 'selected' : ''}>Activo</option>
                                        <option value="0" ${item.estado == '0' ? 'selected' : ''}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-0 justify-content-end row text-center">
                                <div class="col-12">
                                    <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarCliente(${item.id})">Actualizar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    modalsContainer.appendChild(modal);
}

// Actualizar paginación
function actualizarPaginacion(total, porPagina) {
    const textoPaginacion = document.getElementById('texto_paginacion_tabla');
    const listaPaginacion = document.getElementById('lista_paginacion_tabla');
    
    if (total === 0) {
        textoPaginacion.innerHTML = 'Mostrando 0 de 0 registros';
        listaPaginacion.innerHTML = '';
        return;
    }
    
    const totalPaginas = Math.ceil(total / porPagina);
    const inicio = ((currentPage - 1) * porPagina) + 1;
    const fin = Math.min(currentPage * porPagina, total);
    
    textoPaginacion.innerHTML = `Mostrando ${inicio} a ${fin} de ${total} registros`;
    
    // Generar botones de paginación
    let paginacionHTML = '';
    
    // Botón anterior
    if (currentPage > 1) {
        paginacionHTML += `<li class="page-item"><a class="page-link" href="javascript:numero_pagina(${currentPage - 1})">Anterior</a></li>`;
    }
    
    // Números de página
    for (let i = 1; i <= totalPaginas; i++) {
        if (i === currentPage) {
            paginacionHTML += `<li class="page-item active"><a class="page-link" href="javascript:void(0)">${i}</a></li>`;
        } else {
            paginacionHTML += `<li class="page-item"><a class="page-link" href="javascript:numero_pagina(${i})">${i}</a></li>`;
        }
    }
    
    // Botón siguiente
    if (currentPage < totalPaginas) {
        paginacionHTML += `<li class="page-item"><a class="page-link" href="javascript:numero_pagina(${currentPage + 1})">Siguiente</a></li>`;
    }
    
    listaPaginacion.innerHTML = paginacionHTML;
}

// Registrar cliente
// En tu función registrar_cliente() - asegúrate que esto esté correcto:
async function registrar_cliente() {
    const ruc = document.getElementById('ruc').value.trim();
    const razon_social = document.getElementById('razon_social').value.trim();
    
    if (!ruc || !razon_social) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'RUC y Razón Social son obligatorios',
            confirmButtonClass: 'btn btn-confirm mt-2'
        });
        return;
    }
    
    try {
        const formData = new FormData(document.getElementById('frmRegistrar'));
        
        // VERIFICA QUE ESTOS VALORES EXISTAN Y SE ENVÍEN CORRECTAMENTE
        formData.append('sesion', session_session || '');
        formData.append('token', token_token || '');
        
        console.log('Enviando registro con sesión:', session_session); // Para debug
        
        let respuesta = await fetch(base_url_server + 'src/control/ClienteController.php?tipo=registrar', {
            method: 'POST',
            body: formData
        });
        
        let json = await respuesta.json();
        
        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                timer: 1500
            });
            
            document.getElementById("frmRegistrar").reset();
            $('.modal_registrar').modal('hide');
            listar_clientesOrdenados();
            
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2'
            });
        }
    } catch (error) {
        console.error("Error al registrar cliente:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión al registrar cliente',
            confirmButtonClass: 'btn btn-confirm mt-2'
        });
    }
}

// Actualizar cliente
async function actualizarCliente(id) {
    const ruc = document.getElementById(`ruc_${id}`).value.trim();
    const razon_social = document.getElementById(`razon_social_${id}`).value.trim();
    
    if (!ruc || !razon_social) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'RUC y Razón Social son obligatorios',
            confirmButtonClass: 'btn btn-confirm mt-2',
            timer: 1500
        });
        return;
    }
    
    try {
        const formData = new FormData(document.getElementById(`frmActualizar_${id}`));
        formData.append('data', id);
        formData.append('sesion', session_session || '');
        formData.append('token', token_token || '');
        
        let respuesta = await fetch(base_url_server + 'src/control/ClienteController.php?tipo=actualizar', {
            method: 'POST',
            body: formData
        });
        
        let json = await respuesta.json();
        
        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                timer: 1500
            });
            
            $(`.modal_editar_${id}`).modal('hide');
            listar_clientesOrdenados();
            
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                timer: 1500
            });
        }
    } catch (error) {
        console.error("Error al actualizar cliente:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión al actualizar cliente',
            confirmButtonClass: 'btn btn-confirm mt-2'
        });
    }
}

// Eliminar cliente
function eliminar_cliente(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará permanentemente al cliente",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarClienteConfirmado(id);
        }
    });
}

async function eliminarClienteConfirmado(id) {
    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('sesion', session_session || '');
        formData.append('token', token_token || '');

        let respuesta = await fetch(base_url_server + 'src/control/ClienteController.php?tipo=eliminar', {
            method: 'POST',
            body: formData
        });
        
        let json = await respuesta.json();
        
        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                timer: 1500
            });
            listar_clientesOrdenados();
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                timer: 1500
            });
        }
    } catch (error) {
        console.error("Error al eliminar cliente:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión al eliminar cliente',
            confirmButtonClass: 'btn btn-confirm mt-2'
        });
    }
}

// Función de alerta de sesión (debes implementarla según tu sistema)
function alerta_sesion() {
    Swal.fire({
        icon: 'warning',
        title: 'Sesión Expirada',
        text: 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        window.location.reload();
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    listar_clientesOrdenados();
});