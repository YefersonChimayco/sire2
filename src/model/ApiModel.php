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
            error_log("🔍 Búsqueda API - DNI: '$dni', Nombres: '$nombres', Página: $pagina, Límite: $limite");
            
            // CONSULTA CORREGIDA: JOIN con programas_estudio
            $condiciones = [];
            $tipos = "";
            $parametros = [];
            
            // JOIN para obtener el nombre del programa
            $sql = "SELECT e.*, p.nombre as programa_nombre 
                    FROM estudiantes e 
                    LEFT JOIN programas_estudio p ON e.programa_id = p.id 
                    WHERE 1=1";
            
            if (!empty($dni)) {
                $sql .= " AND e.dni LIKE ?";
                $tipos .= "s";
                $parametros[] = $dni . '%';
            }
            
            if (!empty($nombres)) {
                $sql .= " AND (e.nombres LIKE ? OR e.apellido_paterno LIKE ? OR e.apellido_materno LIKE ?)";
                $tipos .= "sss";
                $parametros[] = '%' . $nombres . '%';
                $parametros[] = '%' . $nombres . '%';
                $parametros[] = '%' . $nombres . '%';
            }
            
            error_log("📝 SQL: $sql");
            error_log("📝 Parámetros: " . implode(', ', $parametros));
            
            // Contar total (también con JOIN)
            $sql_count = "SELECT COUNT(*) as total 
                         FROM estudiantes e 
                         LEFT JOIN programas_estudio p ON e.programa_id = p.id 
                         WHERE 1=1";
            if (!empty($dni)) $sql_count .= " AND e.dni LIKE '" . $this->conexion->real_escape_string($dni) . "%'";
            if (!empty($nombres)) $sql_count .= " AND (e.nombres LIKE '%" . $this->conexion->real_escape_string($nombres) . "%' OR e.apellido_paterno LIKE '%" . $this->conexion->real_escape_string($nombres) . "%' OR e.apellido_materno LIKE '%" . $this->conexion->real_escape_string($nombres) . "%')";
            
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
            $sql .= " ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres LIMIT ?, ?";
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
                    'programa_nombre' => $objeto->programa_nombre, // NUEVO: Nombre del programa
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
            
            // CONSULTA CORREGIDA: JOIN con programas_estudio
            $sql = "SELECT e.*, p.nombre as programa_nombre 
                    FROM estudiantes e 
                    LEFT JOIN programas_estudio p ON e.programa_id = p.id 
                    WHERE e.dni = ?";
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
                    'programa_nombre' => $objeto->programa_nombre, // NUEVO: Nombre del programa
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

    // Los demás métodos se mantienen igual...
    public function verificarTabla() {
        // Tu código existente
    }

    public function obtenerDatosPrueba() {
        // Tu código existente
    }
}
?>