<?php
require_once "../library/conexion.php";

class ClienteModel {
    private $conexion;
    
    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarCliente($data) {
        $sql = "INSERT INTO client_api (ruc, razon_social, telefono, correo, fecha_registro, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssi", 
            $data['ruc'], 
            $data['razon_social'], 
            $data['telefono'], 
            $data['correo'], 
            $data['fecha_registro'], 
            $data['estado']
        );
        return $stmt->execute();
    }

    public function actualizarCliente($data) {
        $sql = "UPDATE client_api SET 
                ruc = ?, 
                razon_social = ?, 
                telefono = ?, 
                correo = ?, 
                fecha_registro = ?, 
                estado = ? 
                WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssssii",
            $data['ruc'],
            $data['razon_social'],
            $data['telefono'],
            $data['correo'],
            $data['fecha_registro'],
            $data['estado'],
            $data['id_original']
        );
        return $stmt->execute();
    }

    public function eliminarCliente($id) {
        $sql = "DELETE FROM client_api WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function buscarClienteByRuc($ruc) {
        $sql = "SELECT * FROM client_api WHERE ruc = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $ruc);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    public function buscarClienteById($id) {
        $sql = "SELECT * FROM client_api WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object();
    }

    public function buscarClientesOrderByRazonSocial_tabla_filtro($busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado) {
        $condicion = "1=1";
        $params = [];
        $types = "";
        
        if (!empty($busqueda_tabla_ruc)) {
            $condicion .= " AND ruc LIKE ?";
            $params[] = $busqueda_tabla_ruc . '%';
            $types .= "s";
        }
        if (!empty($busqueda_tabla_razon_social)) {
            $condicion .= " AND razon_social LIKE ?";
            $params[] = '%' . $busqueda_tabla_razon_social . '%';
            $types .= "s";
        }
        if (!empty($busqueda_tabla_estado)) {
            $condicion .= " AND estado = ?";
            $params[] = $busqueda_tabla_estado;
            $types .= "i";
        }

        $sql = "SELECT * FROM client_api WHERE $condicion ORDER BY razon_social";
        $stmt = $this->conexion->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrRespuesta = array();
        while ($objeto = $result->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarClientesOrderByRazonSocial_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado) {
        $condicion = "1=1";
        $params = [];
        $types = "";
        
        if (!empty($busqueda_tabla_ruc)) {
            $condicion .= " AND ruc LIKE ?";
            $params[] = $busqueda_tabla_ruc . '%';
            $types .= "s";
        }
        if (!empty($busqueda_tabla_razon_social)) {
            $condicion .= " AND razon_social LIKE ?";
            $params[] = '%' . $busqueda_tabla_razon_social . '%';
            $types .= "s";
        }
        if (!empty($busqueda_tabla_estado)) {
            $condicion .= " AND estado = ?";
            $params[] = $busqueda_tabla_estado;
            $types .= "i";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $sql = "SELECT * FROM client_api WHERE $condicion ORDER BY razon_social LIMIT ?, ?";
        
        // Agregar parámetros para LIMIT
        $params[] = $iniciar;
        $params[] = $cantidad_mostrar;
        $types .= "ii";
        
        $stmt = $this->conexion->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrRespuesta = array();
        while ($objeto = $result->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    // MÉTODO CORREGIDO - Para contar el total de registros
    public function contarClientesFiltro($busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado) {
        $condicion = "1=1";
        $params = [];
        $types = "";
        
        if (!empty($busqueda_tabla_ruc)) {
            $condicion .= " AND ruc LIKE ?";
            $params[] = $busqueda_tabla_ruc . '%';
            $types .= "s";
        }
        if (!empty($busqueda_tabla_razon_social)) {
            $condicion .= " AND razon_social LIKE ?";
            $params[] = '%' . $busqueda_tabla_razon_social . '%';
            $types .= "s";
        }
        if (!empty($busqueda_tabla_estado)) {
            $condicion .= " AND estado = ?";
            $params[] = $busqueda_tabla_estado;
            $types .= "i";
        }

        $sql = "SELECT COUNT(*) as total FROM client_api WHERE $condicion";
        $stmt = $this->conexion->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_object();
        return $row->total;
    }
}
?>