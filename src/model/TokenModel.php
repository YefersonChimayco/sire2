<?php
require_once "../library/conexion.php";

class TokenModel {
    private $conexion;
    
    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

   public function registrarToken($data) {
    try {
        // Generar token de 16 caracteres + ID del cliente con guión
        $token_base = bin2hex(random_bytes(8)); // 16 caracteres hexadecimales
        $token = $token_base . '-' . $data['id_client_api']; // Agregar ID del cliente con guión
        
        $stmt = $this->conexion->prepare("INSERT INTO tokens (id_client_api, token, fecha_reg, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $data['id_client_api'], $token, $data['fecha_reg'], $data['estado']);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error en registrarToken: " . $e->getMessage());
        return false;
    }
}

    public function actualizarToken($data) {
        try {
            $token = bin2hex(random_bytes(32)); // Generar nuevo token
            $stmt = $this->conexion->prepare("UPDATE tokens SET id_client_api = ?, token = ?, fecha_reg = ?, estado = ? WHERE id = ?");
            $stmt->bind_param("issii", $data['id_client_api'], $token, $data['fecha_reg'], $data['estado'], $data['id_original']);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizarToken: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarToken($id) {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM tokens WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en eliminarToken: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTokenById($id) {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM tokens WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_object();
        } catch (Exception $e) {
            error_log("Error en buscarTokenById: " . $e->getMessage());
            return null;
        }
    }

    public function buscarTokensOrderByFecha_tabla_filtro($busqueda_tabla_cliente, $busqueda_tabla_estado) {
        try {
            $condiciones = [];
            $tipos = "";
            $parametros = [];
            
            $sql = "SELECT t.*, c.razon_social FROM tokens t LEFT JOIN client_api c ON t.id_client_api = c.id WHERE 1=1";
            
            if (!empty($busqueda_tabla_cliente)) {
                $sql .= " AND t.id_client_api = ?";
                $tipos .= "i";
                $parametros[] = $busqueda_tabla_cliente;
            }
            
            if (!empty($busqueda_tabla_estado)) {
                $sql .= " AND t.estado = ?";
                $tipos .= "i";
                $parametros[] = $busqueda_tabla_estado;
            }
            
            $sql .= " ORDER BY t.fecha_reg DESC";
            
            $stmt = $this->conexion->prepare($sql);
            
            if (!empty($parametros)) {
                $stmt->bind_param($tipos, ...$parametros);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $arrRespuesta = [];
            while ($objeto = $result->fetch_object()) {
                $arrRespuesta[] = $objeto;
            }
            return $arrRespuesta;
            
        } catch (Exception $e) {
            error_log("Error en buscarTokensOrderByFecha_tabla_filtro: " . $e->getMessage());
            return [];
        }
    }

    public function buscarTokensOrderByFecha_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_cliente, $busqueda_tabla_estado) {
        try {
            $condiciones = [];
            $tipos = "";
            $parametros = [];
            
            $sql = "SELECT t.*, c.razon_social FROM tokens t LEFT JOIN client_api c ON t.id_client_api = c.id WHERE 1=1";
            
            if (!empty($busqueda_tabla_cliente)) {
                $sql .= " AND t.id_client_api = ?";
                $tipos .= "i";
                $parametros[] = $busqueda_tabla_cliente;
            }
            
            if (!empty($busqueda_tabla_estado)) {
                $sql .= " AND t.estado = ?";
                $tipos .= "i";
                $parametros[] = $busqueda_tabla_estado;
            }
            
            $sql .= " ORDER BY t.fecha_reg DESC LIMIT ?, ?";
            $tipos .= "ii";
            $iniciar = ($pagina - 1) * $cantidad_mostrar;
            $parametros[] = $iniciar;
            $parametros[] = $cantidad_mostrar;
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param($tipos, ...$parametros);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $arrRespuesta = [];
            while ($objeto = $result->fetch_object()) {
                $arrRespuesta[] = $objeto;
            }
            return $arrRespuesta;
            
        } catch (Exception $e) {
            error_log("Error en buscarTokensOrderByFecha_tabla: " . $e->getMessage());
            return [];
        }
    }

    public function getClientes() {
        try {
            $clientes = [];
            $stmt = $this->conexion->prepare("SELECT id, razon_social FROM client_api WHERE estado = 1 ORDER BY razon_social ASC");
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $clientes[$row['id']] = htmlspecialchars($row['razon_social'], ENT_QUOTES, 'UTF-8');
            }
            return $clientes;
        } catch (Exception $e) {
            error_log("Error en getClientes: " . $e->getMessage());
            return [];
        }
    }

    // Método para verificar token (para uso en API)
    public function verificarToken($token) {
        try {
            $stmt = $this->conexion->prepare("SELECT t.*, c.razon_social FROM tokens t 
                                            JOIN client_api c ON t.id_client_api = c.id 
                                            WHERE t.token = ? AND t.estado = 1 AND c.estado = 1");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            return $stmt->get_result()->fetch_object();
        } catch (Exception $e) {
            error_log("Error en verificarToken: " . $e->getMessage());
            return null;
        }
    }
}
?>