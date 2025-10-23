// functions_token.js - Gestión completa de tokens

// Configuración global
const TOKEN_CONFIG = {
    itemsPerPage: 10,
    maxItemsPerPage: 100
};

// Utilidades de seguridad
const SecurityUtils = {
    sanitizeHTML: function(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    },
    
    validateId: function(id) {
        return /^\d+$/.test(id) && parseInt(id) > 0;
    }
};

// Gestión de tokens
class TokenManager {
    constructor() {
        this.currentPage = 1;
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadTokens();
    }
    
    bindEvents() {
        // Eventos de búsqueda
        document.getElementById('busqueda_tabla_cliente')?.addEventListener('change', () => this.loadTokens());
        document.getElementById('busqueda_tabla_estado')?.addEventListener('change', () => this.loadTokens());
        document.getElementById('cantidad_mostrar')?.addEventListener('change', () => this.changePage(1));
    }
    
    async loadTokens() {
        try {
            this.showLoading();
            
            const formData = new FormData();
            formData.append('pagina', this.currentPage);
            formData.append('cantidad_mostrar', this.getItemsPerPage());
            formData.append('busqueda_tabla_cliente', this.getSearchValue('busqueda_tabla_cliente'));
            formData.append('busqueda_tabla_estado', this.getSearchValue('busqueda_tabla_estado'));
            formData.append('sesion', session_session);
            formData.append('token', token_token);
            
            const response = await fetch(`${base_url_server}src/control/TokenController.php?tipo=listar_tokens_ordenados_tabla`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            this.handleResponse(data);
            
        } catch (error) {
            console.error('Error loading tokens:', error);
            this.showError('Error al cargar los tokens');
        } finally {
            this.hideLoading();
        }
    }
    
    handleResponse(data) {
        if (data.status) {
            this.renderTable(data.contenido);
            this.renderPagination(data.total);
            this.renderModals(data.contenido);
            this.updateCounter(data.total);
        } else if (data.msg === "Error_Sesion") {
            this.showSessionError();
        } else {
            this.showError(data.mensaje || 'Error al cargar datos');
        }
    }
    
    renderTable(tokens) {
        const tableContainer = document.getElementById('tablas');
        if (!tableContainer) return;
        
        if (tokens.length === 0) {
            tableContainer.innerHTML = '<div class="alert alert-info">No se encontraron tokens</div>';
            return;
        }
        
        tableContainer.innerHTML = `
            <table class="table table-bordered table-hover dt-responsive" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">Nro</th>
                        <th width="20%">Cliente</th>
                        <th width="45%">Token</th>
                        <th width="15%">Fecha Registro</th>
                        <th width="10%">Estado</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>
                <tbody id="contenido_tabla"></tbody>
            </table>
        `;
        
        const tbody = document.getElementById('contenido_tabla');
        tokens.forEach((token, index) => {
            const row = this.createTableRow(token, index + 1);
            tbody.appendChild(row);
        });
    }
    
    createTableRow(token, number) {
        const row = document.createElement('tr');
        row.className = 'filas_tabla';
        row.id = `fila${token.id}`;
        
        const status = token.estado == 1 ? 
            '<span class="badge badge-success">ACTIVO</span>' : 
            '<span class="badge badge-danger">INACTIVO</span>';
        
        row.innerHTML = `
            <th>${number}</th>
            <td>${SecurityUtils.sanitizeHTML(token.razon_social)}</td>
            <td><code style="font-size: 12px; background: #f8f9fa; padding: 5px; border-radius: 3px; word-break: break-all;">${SecurityUtils.sanitizeHTML(token.token)}</code></td>
            <td>${SecurityUtils.sanitizeHTML(token.fecha_reg)}</td>
            <td>${status}</td>
            <td>${token.options}</td>
        `;
        
        return row;
    }
    
    renderPagination(totalItems) {
        const itemsPerPage = this.getItemsPerPage();
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        // Texto de paginación
        const startItem = (this.currentPage - 1) * itemsPerPage + 1;
        const endItem = Math.min(this.currentPage * itemsPerPage, totalItems);
        
        const paginationText = totalItems > 0 ? 
            `Mostrando ${startItem} a ${endItem} de ${totalItems} registros` : 
            'No hay registros para mostrar';
        
        document.getElementById('texto_paginacion_tabla').innerHTML = paginationText;
        
        // Botones de paginación
        const paginationContainer = document.getElementById('lista_paginacion_tabla');
        paginationContainer.innerHTML = this.generatePaginationHTML(totalPages);
    }
    
    generatePaginationHTML(totalPages) {
        if (totalPages <= 1) return '';
        
        let html = '';
        const maxVisiblePages = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        // Botón anterior
        if (this.currentPage > 1) {
            html += `<li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="tokenManager.changePage(${this.currentPage - 1})">Anterior</a>
            </li>`;
        }
        
        // Páginas
        for (let i = startPage; i <= endPage; i++) {
            const active = i === this.currentPage ? 'active' : '';
            html += `<li class="page-item ${active}">
                <a class="page-link" href="javascript:void(0)" onclick="tokenManager.changePage(${i})">${i}</a>
            </li>`;
        }
        
        // Botón siguiente
        if (this.currentPage < totalPages) {
            html += `<li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="tokenManager.changePage(${this.currentPage + 1})">Siguiente</a>
            </li>`;
        }
        
        return html;
    }
    
    renderModals(tokens) {
        const modalsContainer = document.getElementById('modals_editar');
        if (!modalsContainer) return;
        
        modalsContainer.innerHTML = tokens.map(token => this.createEditModal(token)).join('');
    }
    
    createEditModal(token) {
        const activeSelected = token.estado == 1 ? 'selected' : '';
        const inactiveSelected = token.estado == 0 ? 'selected' : '';
        
        return `
            <div class="modal fade modal_editar${token.id}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h5 class="modal-title h4">Editar Token</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="frmActualizar${token.id}">
                                <input type="hidden" name="data" value="${token.id}">
                                <div class="form-group">
                                    <label>Cliente API</label>
                                    <select name="id_client_api" class="form-control" required>
                                        <option value="">Cargando clientes...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Fecha Registro</label>
                                    <input type="date" name="fecha_reg" class="form-control" value="${token.fecha_reg}" required>
                                </div>
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="1" ${activeSelected}>Activo</option>
                                        <option value="0" ${inactiveSelected}>Inactivo</option>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-success" onclick="tokenManager.updateToken(${token.id})">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // FUNCIÓN PARA REGISTRAR TOKEN - COMPLETA
    async registerToken() {
        try {
            const id_client_api = document.getElementById('id_client_api').value;
            const fecha_reg = document.getElementById('fecha_reg').value;
            const estado = document.getElementById('estado').value;
            
            // Validaciones básicas
            if (!id_client_api) {
                this.showError('Debe seleccionar un cliente');
                return;
            }
            
            if (!fecha_reg) {
                this.showError('La fecha de registro es obligatoria');
                return;
            }
            
            // Preparar datos del formulario
            const formData = new FormData();
            formData.append('id_client_api', id_client_api);
            formData.append('fecha_reg', fecha_reg);
            formData.append('estado', estado);
            formData.append('sesion', session_session);
            formData.append('token', token_token);
            
            console.log('Enviando datos para registrar token:', {
                id_client_api,
                fecha_reg,
                estado
            });
            
            const response = await fetch(`${base_url_server}src/control/TokenController.php?tipo=registrar`, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.status) {
                this.showSuccess(data.mensaje);
                // Limpiar formulario y cerrar modal
                document.getElementById('frmRegistrar').reset();
                document.getElementById('fecha_reg').value = new Date().toISOString().split('T')[0];
                $('.modal_registrar').modal('hide');
                // Recargar lista de tokens
                this.loadTokens();
            } else {
                this.showError(data.mensaje);
            }
            
        } catch (error) {
            console.error('Error registering token:', error);
            this.showError('Error al registrar token: ' + error.message);
        }
    }
    
    async updateToken(id) {
        if (!SecurityUtils.validateId(id)) {
            this.showError('ID no válido');
            return;
        }
        
        try {
            const form = document.getElementById(`frmActualizar${id}`);
            if (!form) return;
            
            const formData = new FormData(form);
            formData.append('sesion', session_session);
            formData.append('token', token_token);
            
            const response = await fetch(`${base_url_server}src/control/TokenController.php?tipo=actualizar`, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.status) {
                this.showSuccess(data.mensaje);
                $(`.modal_editar${id}`).modal('hide');
                this.loadTokens();
            } else {
                this.showError(data.mensaje);
            }
            
        } catch (error) {
            console.error('Error updating token:', error);
            this.showError('Error al actualizar token');
        }
    }
    
    async deleteToken(id) {
        if (!SecurityUtils.validateId(id)) {
            this.showError('ID no válido');
            return;
        }
        
        const result = await Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });
        
        if (result.isConfirmed) {
            try {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('sesion', session_session);
                formData.append('token', token_token);
                
                const response = await fetch(`${base_url_server}src/control/TokenController.php?tipo=eliminar`, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.status) {
                    this.showSuccess(data.mensaje);
                    this.loadTokens();
                } else {
                    this.showError(data.mensaje);
                }
                
            } catch (error) {
                console.error('Error deleting token:', error);
                this.showError('Error al eliminar token');
            }
        }
    }
    
    changePage(page) {
        this.currentPage = page;
        document.getElementById('pagina').value = page;
        this.loadTokens();
    }
    
    getItemsPerPage() {
        const select = document.getElementById('cantidad_mostrar');
        return select ? parseInt(select.value) : TOKEN_CONFIG.itemsPerPage;
    }
    
    getSearchValue(fieldName) {
        const element = document.getElementById(fieldName);
        return element ? element.value : '';
    }
    
    updateCounter(total) {
        const counter = document.getElementById('contadorTokens');
        if (counter) {
            counter.textContent = `${total} tokens encontrados`;
        }
    }
    
    showLoading() {
        const tablas = document.getElementById('tablas');
        if (tablas) {
            tablas.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando tokens...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando tokens...</p>
                </div>
            `;
        }
    }
    
    hideLoading() {
        // El contenido se reemplaza automáticamente
    }
    
    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: message,
            timer: 3000
        });
    }
    
    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }
    
    showSessionError() {
        Swal.fire({
            icon: 'warning',
            title: 'Sesión expirada',
            text: 'Por favor, inicie sesión nuevamente',
            willClose: () => {
                window.location.reload();
            }
        });
    }
}

// Inicializar manager
const tokenManager = new TokenManager();

// Funciones globales para compatibilidad
function numero_pagina(pagina) {
    tokenManager.changePage(pagina);
}

function listar_tokensOrdenados() {
    tokenManager.loadTokens();
}

// FUNCIÓN PARA REGISTRAR TOKEN - EXPUESTA GLOBALMENTE
function registrar_token() {
    tokenManager.registerToken();
}

function actualizar_token(id) {
    tokenManager.updateToken(id);
}

function eliminar_token(id) {
    tokenManager.deleteToken(id);
}

// Función para cargar clientes en los selects
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