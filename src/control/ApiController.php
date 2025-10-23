<?php
/**
 * ApiController.php - Controlador para API de Estudiantes
 * NO MODIFICAR - Ya funciona correctamente
 */

header('Content-Type: application/json; charset=utf-8');

// Cargar modelos
require_once('../model/ApiModel.php');

try {
    $tipo = $_GET['tipo'] ?? '';
    
    // Token fijo - se valida automáticamente
    $api_token = '3830e33370edc261-1';
    
    // Instanciar modelo
    $objApi = new ApiModel();
    
    // DEBUG: Verificar tabla y datos
    if ($tipo === 'debug') {
        $tablaExiste = $objApi->verificarTabla();
        $datosPrueba = $objApi->obtenerDatosPrueba();
        
        echo json_encode([
            'status' => true,
            'debug' => [
                'tabla_existe' => $tablaExiste,
                'datos_prueba' => $datosPrueba,
                'total_datos' => count($datosPrueba)
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Validar token fijo
    if ($api_token !== '3830e33370edc261-1') {
        throw new Exception('Token de API inválido', 401);
    }
    
    // Procesar según el tipo
    switch($tipo) {
        case 'buscar_estudiantes':
            $dni = $_POST['dni'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $pagina = intval($_POST['pagina'] ?? 1);
            $limite = intval($_POST['limite'] ?? 20);
            
            // Validar límite máximo
            if ($limite > 100) $limite = 100;
            if ($pagina < 1) $pagina = 1;
            
            $resultados = $objApi->buscarEstudiantes($dni, $nombres, $pagina, $limite);
            
            echo json_encode([
                'status' => true,
                'data' => $resultados['estudiantes'],
                'paginacion' => $resultados['paginacion']
            ], JSON_UNESCAPED_UNICODE);
            break;
            
        case 'buscar_estudiante_dni':
            $dni = $_POST['dni'] ?? '';
            
            if (empty($dni)) {
                throw new Exception('DNI requerido', 400);
            }
            
            $estudiante = $objApi->buscarEstudiantePorDNI($dni);
            
            if ($estudiante) {
                echo json_encode([
                    'status' => true,
                    'data' => $estudiante
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'status' => false,
                    'error' => 'Estudiante no encontrado'
                ], JSON_UNESCAPED_UNICODE);
            }
            break;
            
        default:
            throw new Exception('Endpoint no válido: ' . $tipo, 404);
    }
    
} catch (Exception $e) {
    $code = $e->getCode() ?: 500;
    http_response_code($code);
    
    echo json_encode([
        'status' => false,
        'error' => $e->getMessage(),
        'code' => $code
    ], JSON_UNESCAPED_UNICODE);
}
?>