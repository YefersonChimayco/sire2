<?php
session_start();
require_once('../model/admin-sesionModel.php');
require_once('../model/TokenModel.php');

class TokenController {
    private $objSesion;
    private $objToken;
    
    public function __construct() {
        $this->objSesion = new SessionModel();
        $this->objToken = new TokenModel();
        
        // ========== CONFIGURACIÓN CORS COMPLETA ==========
        $this->configurarCORS();
    }
    
    // ========== NUEVO MÉTODO: CONFIGURAR CORS ==========
    private function configurarCORS() {
        // Permitir acceso desde cualquier origen (en producción puedes restringir a tu dominio)
        header("Access-Control-Allow-Origin: *");
        // Permitir los métodos HTTP necesarios
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        // Permitir los headers personalizados
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin");
        // Permitir credenciales (si es necesario)
        header("Access-Control-Allow-Credentials: true");
        // Cache preflight por 1 hora
        header("Access-Control-Max-Age: 3600");
        
        // Manejar preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
    
    private function validateSession($id_sesion, $token_sesion) {
        return $this->objSesion->verificar_sesion_si_activa($id_sesion, $token_sesion);
    }
    
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    private function jsonResponse($data) {
        // Limpiar buffer de salida
        while (ob_get_level()) ob_end_clean();
        
        // Headers para JSON
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    public function handleRequest($tipo) {
        // ========== ENDPOINT PÚBLICO - NO REQUIERE SESIÓN ==========
        if ($tipo === "validar_token_api") {
            $this->validarTokenAPI();
            return;
        }
        
        $id_sesion = $_POST['sesion'] ?? '';
        $token_sesion = $_POST['token'] ?? '';
        
        // Validar sesión para endpoints protegidos
        if (!$this->validateSession($id_sesion, $token_sesion)) {
            $this->jsonResponse(['status' => false, 'msg' => 'Error_Sesion']);
        }
        
        switch($tipo) {
            case "listar_tokens_ordenados_tabla":
                $this->listarTokens();
                break;
            case "registrar":
                $this->registrarToken();
                break;
            case "actualizar":
                $this->actualizarToken();
                break;
            case "eliminar":
                $this->eliminarToken();
                break;
            case "get_clientes":
                $this->getClientes();
                break;
            default:
                $this->jsonResponse(['status' => false, 'msg' => 'Tipo no válido']);
        }
    }
    
    // ========== MÉTODO VALIDAR TOKEN API ==========
    private function validarTokenAPI() {
        try {
            // Obtener token de POST, GET o raw input
            $token = $_POST['token'] ?? $_GET['token'] ?? '';
            
            // Si no viene por POST/GET, intentar leer raw input
            if (empty($token)) {
                $input = file_get_contents('php://input');
                if (!empty($input)) {
                    $data = json_decode($input, true);
                    $token = $data['token'] ?? '';
                }
            }
            
            if (empty($token)) {
                $this->jsonResponse([
                    'status' => false,
                    'error' => 'Token no proporcionado',
                    'codigo' => 400,
                    'valido' => false
                ]);
            }
            
            // Log para debugging
            error_log("🔐 Validando token: " . substr($token, 0, 10) . "...");
            
            // Usar método existente en TokenModel
            $tokenData = $this->objToken->validarTokenAPI($token);
            
            if ($tokenData) {
                error_log("✅ Token válido para cliente: " . $tokenData->razon_social);
                
                $this->jsonResponse([
                    'status' => true,
                    'mensaje' => 'Token válido',
                    'valido' => true,
                    'cliente' => [
                        'id' => $tokenData->id_client_api,
                        'razon_social' => $tokenData->razon_social,
                        'ruc' => $tokenData->ruc ?? '',
                        'correo' => $tokenData->correo ?? '',
                        'telefono' => $tokenData->telefono ?? ''
                    ],
                    'token_info' => [
                        'id' => $tokenData->id,
                        'fecha_reg' => $tokenData->fecha_reg,
                        'estado' => $tokenData->estado
                    ],
                    'codigo' => 200
                ]);
            } else {
                error_log("❌ Token inválido: " . substr($token, 0, 10) . "...");
                
                $this->jsonResponse([
                    'status' => false,
                    'error' => 'Token inválido, expirado o cliente inactivo',
                    'valido' => false,
                    'codigo' => 401
                ]);
            }
            
        } catch (Exception $e) {
            error_log("💥 Error en validarTokenAPI: " . $e->getMessage());
            $this->jsonResponse([
                'status' => false,
                'error' => 'Error interno del servidor: ' . $e->getMessage(),
                'valido' => false,
                'codigo' => 500
            ]);
        }
    }
    
    // ========== MÉTODOS EXISTENTES (SIN CAMBIOS) ==========
    private function getClientes() {
        try {
            $clientes = $this->objToken->getClientes();
            
            $clientesFormateados = [];
            foreach ($clientes as $id => $razon_social) {
                $clientesFormateados[] = [
                    'id' => $id,
                    'razon_social' => $razon_social
                ];
            }
            
            $this->jsonResponse([
                'status' => true,
                'clientes' => $clientesFormateados
            ]);
            
        } catch (Exception $e) {
            error_log("Error en getClientes: " . $e->getMessage());
            $this->jsonResponse(['status' => false, 'msg' => 'Error al cargar clientes']);
        }
    }
    
    private function listarTokens() {
        try {
            $pagina = filter_var($_POST['pagina'] ?? 1, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'default' => 1]
            ]);
            
            $cantidad_mostrar = filter_var($_POST['cantidad_mostrar'] ?? 10, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 100, 'default' => 10]
            ]);
            
            $busqueda_tabla_cliente = $this->sanitizeInput($_POST['busqueda_tabla_cliente'] ?? '');
            $busqueda_tabla_estado = $this->sanitizeInput($_POST['busqueda_tabla_estado'] ?? '');
            
            $busqueda_filtro = $this->objToken->buscarTokensOrderByFecha_tabla_filtro(
                $busqueda_tabla_cliente, 
                $busqueda_tabla_estado
            );
            
            $arr_Tokens = $this->objToken->buscarTokensOrderByFecha_tabla(
                $pagina, 
                $cantidad_mostrar, 
                $busqueda_tabla_cliente, 
                $busqueda_tabla_estado
            );
            
            $arr_contenido = [];
            if (!empty($arr_Tokens)) {
                foreach ($arr_Tokens as $i => $token) {
                    $arr_contenido[$i] = (object) [
                        'id' => $token->id,
                        'id_client_api' => $token->id_client_api,
                        'token' => $token->token,
                        'fecha_reg' => $token->fecha_reg,
                        'estado' => $token->estado,
                        'razon_social' => $this->sanitizeInput($token->razon_social ?? 'No asignado'),
                        'options' => $this->generateOptions($token->id)
                    ];
                }
                
                $this->jsonResponse([
                    'status' => true,
                    'total' => count($busqueda_filtro),
                    'contenido' => $arr_contenido
                ]);
            } else {
                $this->jsonResponse(['status' => true, 'total' => 0, 'contenido' => []]);
            }
            
        } catch (Exception $e) {
            error_log("Error en listarTokens: " . $e->getMessage());
            $this->jsonResponse(['status' => false, 'msg' => 'Error interno del servidor']);
        }
    }
    
    private function generateOptions($id) {
        $id = intval($id);
        return '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" 
                data-toggle="modal" data-target=".modal_editar' . $id . '">
                <i class="fa fa-edit"></i></button>
                <button class="btn btn-danger waves-effect waves-light" title="Eliminar" 
                onclick="eliminar_token(' . $id . ')">
                <i class="fa fa-trash"></i></button>';
    }
    
    private function registrarToken() {
        try {
            $id_client_api = filter_var($_POST['id_client_api'] ?? '', FILTER_VALIDATE_INT);
            $fecha_reg = $this->sanitizeInput($_POST['fecha_reg'] ?? date('Y-m-d'));
            $estado = filter_var($_POST['estado'] ?? 1, FILTER_VALIDATE_INT);
            
            if (!$id_client_api || $id_client_api <= 0) {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Cliente API no válido']);
            }
            
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_reg)) {
                $fecha_reg = date('Y-m-d');
            }
            
            $data = [
                'id_client_api' => $id_client_api,
                'fecha_reg' => $fecha_reg,
                'estado' => $estado
            ];
            
            $success = $this->objToken->registrarToken($data);
            if ($success) {
                $this->jsonResponse(['status' => true, 'mensaje' => 'Token registrado exitosamente']);
            } else {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Error al registrar token']);
            }
            
        } catch (Exception $e) {
            error_log("Error en registrarToken: " . $e->getMessage());
            $this->jsonResponse(['status' => false, 'mensaje' => 'Error interno del servidor']);
        }
    }
    
    private function actualizarToken() {
        try {
            $id_original = filter_var($_POST['data'] ?? '', FILTER_VALIDATE_INT);
            $id_client_api = filter_var($_POST['id_client_api'] ?? '', FILTER_VALIDATE_INT);
            $fecha_reg = $this->sanitizeInput($_POST['fecha_reg'] ?? '');
            $estado = filter_var($_POST['estado'] ?? 1, FILTER_VALIDATE_INT);
            
            if (!$id_original || $id_original <= 0) {
                $this->jsonResponse(['status' => false, 'mensaje' => 'ID no válido']);
            }
            
            if (!$id_client_api || $id_client_api <= 0) {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Cliente API no válido']);
            }
            
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_reg)) {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Formato de fecha no válido']);
            }
            
            $data = [
                'id_original' => $id_original,
                'id_client_api' => $id_client_api,
                'fecha_reg' => $fecha_reg,
                'estado' => $estado
            ];
            
            $success = $this->objToken->actualizarToken($data);
            if ($success) {
                $this->jsonResponse(['status' => true, 'mensaje' => 'Token actualizado correctamente']);
            } else {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Error al actualizar token']);
            }
            
        } catch (Exception $e) {
            error_log("Error en actualizarToken: " . $e->getMessage());
            $this->jsonResponse(['status' => false, 'mensaje' => 'Error interno del servidor']);
        }
    }
    
    private function eliminarToken() {
        try {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            
            if (!$id || $id <= 0) {
                $this->jsonResponse(['status' => false, 'mensaje' => 'ID no válido']);
            }
            
            $token = $this->objToken->buscarTokenById($id);
            if (!$token) {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Token no encontrado']);
            }
            
            $success = $this->objToken->eliminarToken($id);
            if ($success) {
                $this->jsonResponse(['status' => true, 'mensaje' => 'Token eliminado correctamente']);
            } else {
                $this->jsonResponse(['status' => false, 'mensaje' => 'Error al eliminar token']);
            }
            
        } catch (Exception $e) {
            error_log("Error en eliminarToken: " . $e->getMessage());
            $this->jsonResponse(['status' => false, 'mensaje' => 'Error interno del servidor']);
        }
    }
}

// Ejecutar controlador
$controller = new TokenController();
$tipo = $_GET['tipo'] ?? '';
$controller->handleRequest($tipo);
?>