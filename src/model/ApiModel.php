<?php
require_once "../library/conexion.php";

class ApiModel {
    private $conexion;
    
    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function buscarEstudiantes($dni, $nombres, $pagina, $limite) {
        try {
            // DEBUG: Log de parámetros
            error_log("🔍 Búsqueda API - DNI: '$dni', Nombres: '$nombres', Página: $pagina, Límite: $limite");
            
            // Construir consulta usando la tabla ESTUDIANTES
            $condiciones = [];
            $tipos = "";
            $parametros = [];
            
            // Usar la tabla estudiantes en lugar de la vista
            $sql = "SELECT * FROM estudiantes WHERE 1=1";
            
            if (!empty($dni)) {
                $sql .= " AND dni LIKE ?";
                $tipos .= "s";
                $parametros[] = $dni . '%';
            }
            
            if (!empty($nombres)) {
                $sql .= " AND (nombres LIKE ? OR apellido_paterno LIKE ? OR apellido_materno LIKE ?)";
                $tipos .= "sss";
                $parametros[] = '%' . $nombres . '%';
                $parametros[] = '%' . $nombres . '%';
                $parametros[] = '%' . $nombres . '%';
            }
            
            // DEBUG: Ver SQL construido
            error_log("📝 SQL: $sql");
            error_log("📝 Parámetros: " . implode(', ', $parametros));
            
            // Contar total
            $sql_count = "SELECT COUNT(*) as total FROM estudiantes WHERE 1=1";
            if (!empty($dni)) $sql_count .= " AND dni LIKE '" . $this->conexion->real_escape_string($dni) . "%'";
            if (!empty($nombres)) $sql_count .= " AND (nombres LIKE '%" . $this->conexion->real_escape_string($nombres) . "%' OR apellido_paterno LIKE '%" . $this->conexion->real_escape_string($nombres) . "%' OR apellido_materno LIKE '%" . $this->conexion->real_escape_string($nombres) . "%')";
            
            error_log("📊 SQL Count: $sql_count");
            
            $result_count = $this->conexion->query($sql_count);
            if (!$result_count) {
                error_log("❌ Error en COUNT: " . $this->conexion->error);
                throw new Exception("Error en consulta COUNT: " . $this->conexion->error);
            }
            
            $total_row = $result_count->fetch_object();
            $total_estudiantes = $total_row->total;
            
            error_log("📊 Total estudiantes: $total_estudiantes");
            
            // Aplicar paginación
            $sql .= " ORDER BY apellido_paterno, apellido_materno, nombres LIMIT ?, ?";
            $tipos .= "ii";
            $iniciar = ($pagina - 1) * $limite;
            $parametros[] = $iniciar;
            $parametros[] = $limite;
            
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) {
                error_log("❌ Error preparando consulta: " . $this->conexion->error);
                throw new Exception("Error preparando consulta: " . $this->conexion->error);
            }
            
            if (!empty($parametros)) {
                $stmt->bind_param($tipos, ...$parametros);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $estudiantes = [];
            while ($objeto = $result->fetch_object()) {
                $estudiantes[] = [
                    'dni' => $objeto->dni,
                    'nombres' => $objeto->nombres,
                    'apellido_paterno' => $objeto->apellido_paterno,
                    'apellido_materno' => $objeto->apellido_materno,
                    'nombre_completo' => trim($objeto->nombres . ' ' . $objeto->apellido_paterno . ' ' . $objeto->apellido_materno),
                    'estado' => $objeto->estado,
                    'semestre' => intval($objeto->semestre),
                    'programa_id' => intval($objeto->programa_id),
                    'fecha_matricula' => $objeto->fecha_matricula
                ];
            }
            
            error_log("✅ Estudiantes encontrados: " . count($estudiantes));
            
            return [
                'estudiantes' => $estudiantes,
                'paginacion' => [
                    'pagina_actual' => $pagina,
                    'limite' => $limite,
                    'total_estudiantes' => $total_estudiantes,
                    'total_paginas' => ceil($total_estudiantes / $limite)
                ]
            ];
            
        } catch (Exception $e) {
            error_log("❌ Error en buscarEstudiantes: " . $e->getMessage());
            return [
                'estudiantes' => [],
                'paginacion' => [
                    'pagina_actual' => 1,
                    'limite' => 20,
                    'total_estudiantes' => 0,
                    'total_paginas' => 0
                ]
            ];
        }
    }
    
    public function buscarEstudiantePorDNI($dni) {
        try {
            error_log("🔍 Búsqueda por DNI: '$dni'");
            
            // Usar la tabla estudiantes
            $sql = "SELECT * FROM estudiantes WHERE dni = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("s", $dni);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($objeto = $result->fetch_object()) {
                error_log("✅ Estudiante encontrado por DNI: $dni");
                return [
                    'dni' => $objeto->dni,
                    'nombres' => $objeto->nombres,
                    'apellido_paterno' => $objeto->apellido_paterno,
                    'apellido_materno' => $objeto->apellido_materno,
                    'nombre_completo' => trim($objeto->nombres . ' ' . $objeto->apellido_paterno . ' ' . $objeto->apellido_materno),
                    'estado' => $objeto->estado,
                    'semestre' => intval($objeto->semestre),
                    'programa_id' => intval($objeto->programa_id),
                    'fecha_matricula' => $objeto->fecha_matricula
                ];
            }
            
            error_log("❌ Estudiante NO encontrado por DNI: $dni");
            return null;
            
        } catch (Exception $e) {
            error_log("❌ Error en buscarEstudiantePorDNI: " . $e->getMessage());
            return null;
        }
    }

    // MÉTODO PARA VERIFICAR SI LA TABLA EXISTE
    public function verificarTabla() {
        try {
            $sql = "SELECT COUNT(*) as existe FROM information_schema.tables 
                    WHERE table_schema = DATABASE() AND table_name = 'estudiantes'";
            $result = $this->conexion->query($sql);
            $row = $result->fetch_object();
            
            error_log("🔍 Verificando tabla estudiantes: " . ($row->existe ? "EXISTE" : "NO EXISTE"));
            
            return $row->existe > 0;
        } catch (Exception $e) {
            error_log("❌ Error verificando tabla: " . $e->getMessage());
            return false;
        }
    }

    // MÉTODO PARA VER DATOS DE PRUEBA EN LA TABLA
    public function obtenerDatosPrueba() {
        try {
            $sql = "SELECT * FROM estudiantes LIMIT 5";
            $result = $this->conexion->query($sql);
            
            $datos = [];
            while ($objeto = $result->fetch_object()) {
                $datos[] = $objeto;
            }
            
            error_log("📋 Datos de prueba en tabla: " . count($datos) . " registros");
            foreach ($datos as $dato) {
                error_log("📝 DNI: {$dato->dni}, Nombre: {$dato->nombres} {$dato->apellido_paterno}");
            }
            
            return $datos;
        } catch (Exception $e) {
            error_log("❌ Error obteniendo datos prueba: " . $e->getMessage());
            return [];
        }
    }
}
?>