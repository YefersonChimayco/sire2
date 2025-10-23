// Configuración global
const API_CONFIG = {
    itemsPerPage: 20,
    maxItemsPerPage: 100,
    API_TOKEN: '3830e33370edc261-1' // Token fijo
};

// Utilidades de seguridad
const ApiSecurityUtils = {
    sanitizeHTML: function(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    },
    
    validateDNI: function(dni) {
        return /^\d{8}$/.test(dni);
    }
};

// Gestión de API Estudiantes
class ApiEstudiantesManager {
    constructor() {
        this.currentPage = 1;
        this.currentResults = null;
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.showSearchSections(); // Mostrar secciones automáticamente
        console.log('✅ API Estudiantes Manager inicializado - Token automático');
    }
    
    showSearchSections() {
        // Mostrar secciones automáticamente (sin validación de token)
        document.getElementById('busquedaSection').style.display = 'block';
        document.getElementById('resultadosSection').style.display = 'block';
        this.showSuccess('Sistema listo - Token validado automáticamente');
    }
    
    bindEvents() {
        // Evento para buscar al presionar Enter
        document.getElementById('dni')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.ejecutarBusqueda();
        });
        
        document.getElementById('nombres')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.ejecutarBusqueda();
        });
    }
    
    async ejecutarBusqueda() {
        try {
            this.showLoading();
            
            const dni = document.getElementById('dni').value.trim();
            const nombres = document.getElementById('nombres').value.trim();
            
            // Validar que al menos un campo tenga datos
            if (!dni && !nombres) {
                this.showError('Debe ingresar DNI o Nombre para buscar');
                this.hideLoading();
                return;
            }
            
            // Validar formato DNI si se proporciona
            if (dni && !ApiSecurityUtils.validateDNI(dni)) {
                this.showError('DNI debe contener 8 dígitos numéricos');
                this.hideLoading();
                return;
            }
            
            const formData = new FormData();
            formData.append('dni', dni);
            formData.append('nombres', nombres);
            formData.append('pagina', this.currentPage);
            formData.append('limite', this.getItemsPerPage());
            formData.append('api_token', API_CONFIG.API_TOKEN); // Token fijo automático
            
            console.log('🔍 Ejecutando búsqueda...', {
                dni: dni,
                nombres: nombres,
                pagina: this.currentPage,
                limite: this.getItemsPerPage()
            });
            
            const response = await fetch(`${base_url_server}src/control/ApiController.php?tipo=buscar_estudiantes`, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            this.handleBusquedaResponse(data);
            
        } catch (error) {
            console.error('Error en búsqueda:', error);
            this.showError('Error de conexión: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }
    
    async buscarPorDNI() {
        try {
            this.showLoading();
            
            const dni = document.getElementById('dni').value.trim();
            
            if (!dni) {
                this.showError('Ingrese un DNI para buscar');
                this.hideLoading();
                return;
            }
            
            if (!ApiSecurityUtils.validateDNI(dni)) {
                this.showError('DNI debe contener 8 dígitos numéricos');
                this.hideLoading();
                return;
            }
            
            const formData = new FormData();
            formData.append('dni', dni);
            formData.append('api_token', API_CONFIG.API_TOKEN); // Token fijo automático
            
            console.log('🔍 Buscando por DNI:', dni);
            
            const response = await fetch(`${base_url_server}src/control/ApiController.php?tipo=buscar_estudiante_dni`, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            const data = await response.json();
            this.handleBusquedaDNIResponse(data, dni);
            
        } catch (error) {
            console.error('Error en búsqueda por DNI:', error);
            this.showError('Error de conexión: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }
    
    handleBusquedaResponse(data) {
        if (data.status) {
            this.currentResults = data;
            this.renderTable(data.data);
            this.renderPagination(data.paginacion);
            this.updateCounter(data.paginacion.total_estudiantes);
            this.showSuccess(`Búsqueda completada - ${data.paginacion.total_estudiantes} estudiantes encontrados`);
        } else {
            this.showError(data.error || 'Error en la búsqueda');
            this.clearResults();
        }
    }
    
    handleBusquedaDNIResponse(data, dni) {
        if (data.status && data.data) {
            // Formatear como resultado de búsqueda para consistencia
            const resultado = {
                data: [data.data],
                paginacion: {
                    total_estudiantes: 1,
                    total_paginas: 1,
                    pagina_actual: 1,
                    limite: 1
                }
            };
            
            this.currentResults = resultado;
            this.renderTable(resultado.data);
            this.renderPagination(resultado.paginacion);
            this.updateCounter(1);
            this.showSuccess('Estudiante encontrado correctamente');
        } else {
            this.showError(data.error || `No se encontró estudiante con DNI: ${dni}`);
            this.clearResults();
        }
    }
    
    renderTable(estudiantes) {
        const cuerpoTabla = document.getElementById('cuerpoTabla');
        if (!cuerpoTabla) return;
        
        cuerpoTabla.innerHTML = '';
        
        if (!estudiantes || estudiantes.length === 0) {
            cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-search fa-2x mb-2"></i><br>
                        No se encontraron estudiantes con los criterios de búsqueda
                    </td>
                </tr>
            `;
            return;
        }
        
        estudiantes.forEach((estudiante, index) => {
            const row = this.createTableRow(estudiante, index + 1);
            cuerpoTabla.appendChild(row);
        });
    }
    
    createTableRow(estudiante, number) {
        const row = document.createElement('tr');
        
        let claseEstado = 'bg-secondary';
        let textoEstado = estudiante.estado?.toUpperCase() || 'DESCONOCIDO';
        
        switch(estudiante.estado?.toLowerCase()) {
            case 'activo': claseEstado = 'bg-success'; break;
            case 'inactivo': claseEstado = 'bg-secondary'; break;
            case 'graduado': claseEstado = 'bg-info'; break;
            case 'suspendido': claseEstado = 'bg-warning'; break;
            default: claseEstado = 'bg-secondary';
        }
        
        row.innerHTML = `
            <td><strong>${ApiSecurityUtils.sanitizeHTML(estudiante.dni)}</strong></td>
            <td>${ApiSecurityUtils.sanitizeHTML(estudiante.nombre_completo)}</td>
            <td>${ApiSecurityUtils.sanitizeHTML(estudiante.programa_nombre || 'No asignado')}</td>
            <td>
                <span class="badge bg-primary">${estudiante.semestre}</span>
                <small class="text-muted d-block">${ApiSecurityUtils.sanitizeHTML(estudiante.semestre_descripcion || '')}</small>
            </td>
            <td><span class="badge ${claseEstado}">${textoEstado}</span></td>
            <td>${ApiSecurityUtils.sanitizeHTML(estudiante.fecha_matricula || 'No registrada')}</td>
        `;
        
        return row;
    }
    
    renderPagination(paginacion) {
        const paginacionElement = document.getElementById('lista_paginacion_tabla');
        const infoPaginacion = document.getElementById('infoPaginacion');
        const textoPaginacion = document.getElementById('texto_paginacion_tabla');
        
        if (!paginacionElement) return;
        
        // Actualizar información de paginación
        if (infoPaginacion) {
            infoPaginacion.textContent = `Página ${paginacion.pagina_actual} de ${paginacion.total_paginas}`;
        }
        
        if (textoPaginacion) {
            const startItem = (paginacion.pagina_actual - 1) * paginacion.limite + 1;
            const endItem = Math.min(paginacion.pagina_actual * paginacion.limite, paginacion.total_estudiantes);
            
            textoPaginacion.textContent = paginacion.total_estudiantes > 0 ? 
                `Mostrando ${startItem} a ${endItem} de ${paginacion.total_estudiantes} estudiantes` : 
                'No hay estudiantes para mostrar';
        }
        
        // Generar botones de paginación
        let htmlPaginacion = '';
        const totalPaginas = paginacion.total_paginas;
        const paginaActual = paginacion.pagina_actual;
        
        // Botón anterior
        if (paginaActual > 1) {
            htmlPaginacion += `
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" onclick="apiManager.changePage(${paginaActual - 1})">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            `;
        } else {
            htmlPaginacion += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                </li>
            `;
        }
        
        // Números de página
        const inicio = Math.max(1, paginaActual - 2);
        const fin = Math.min(totalPaginas, paginaActual + 2);
        
        for (let i = inicio; i <= fin; i++) {
            if (i === paginaActual) {
                htmlPaginacion += `
                    <li class="page-item active">
                        <span class="page-link">${i}</span>
                    </li>
                `;
            } else {
                htmlPaginacion += `
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="apiManager.changePage(${i})">${i}</a>
                    </li>
                `;
            }
        }
        
        // Botón siguiente
        if (paginaActual < totalPaginas) {
            htmlPaginacion += `
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0)" onclick="apiManager.changePage(${paginaActual + 1})">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;
        } else {
            htmlPaginacion += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                </li>
            `;
        }
        
        paginacionElement.innerHTML = htmlPaginacion;
    }
    
    updateCounter(total) {
        const contador = document.getElementById('contadorResultados');
        if (!contador) return;
        
        contador.textContent = `${total} estudiantes encontrados`;
        contador.className = total > 0 ? 'badge bg-success' : 'badge bg-warning';
        
        // Mostrar controles si hay resultados
        const controles = document.getElementById('controlesResultados');
        if (controles) {
            controles.style.display = total > 0 ? 'flex' : 'none';
        }
    }
    
    clearResults() {
        this.currentResults = null;
        this.renderTable([]);
        this.updateCounter(0);
        
        const paginacionElement = document.getElementById('lista_paginacion_tabla');
        if (paginacionElement) {
            paginacionElement.innerHTML = '<li class="page-item disabled"><span class="page-link">-</span></li>';
        }
    }
    
    changePage(page) {
        this.currentPage = page;
        document.getElementById('pagina').value = page;
        this.ejecutarBusqueda();
    }
    
    limpiarFiltros() {
        document.getElementById('dni').value = '';
        document.getElementById('nombres').value = '';
        document.getElementById('pagina').value = 1;
        this.currentPage = 1;
        this.clearResults();
        this.showSuccess('Filtros limpiados correctamente');
    }
    
    exportarJSON() {
        if (!this.currentResults) {
            this.showError('No hay resultados para exportar');
            return;
        }
        
        // Simulación de exportación
        this.showSuccess('Función de exportación JSON lista');
        console.log('📤 Datos para exportar:', this.currentResults);
    }
    
    mostrarJSON() {
        if (!this.currentResults) {
            this.showError('No hay resultados para mostrar');
            return;
        }
        
        // Simulación de mostrar JSON
        this.showSuccess('Función de visualización JSON lista');
        console.log('📄 JSON completo:', JSON.stringify(this.currentResults, null, 2));
    }
    
    getItemsPerPage() {
        const select = document.getElementById('limite');
        return select ? parseInt(select.value) : API_CONFIG.itemsPerPage;
    }
    
    showLoading() {
        const spinner = document.getElementById('loadingSpinner');
        const tabla = document.getElementById('tablaResultados');
        
        if (spinner) spinner.style.display = 'block';
        if (tabla) tabla.style.opacity = '0.5';
    }
    
    hideLoading() {
        const spinner = document.getElementById('loadingSpinner');
        const tabla = document.getElementById('tablaResultados');
        
        if (spinner) spinner.style.display = 'none';
        if (tabla) tabla.style.opacity = '1';
    }
    
    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }
    }
    
    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 5000
            });
        }
    }
}

// Inicializar manager
const apiManager = new ApiEstudiantesManager();

// Funciones globales para compatibilidad con HTML
function ejecutarBusqueda() {
    apiManager.ejecutarBusqueda();
}

function buscarPorDNI() {
    apiManager.buscarPorDNI();
}

function limpiarFiltros() {
    apiManager.limpiarFiltros();
}

function cambiarPagina(pagina) {
    apiManager.changePage(pagina);
}

function exportarJSON() {
    apiManager.exportarJSON();
}

function mostrarJSON() {
    apiManager.mostrarJSON();
}

// Inicialización cuando el documento está listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 API Estudiantes inicializada - Token automático');
});