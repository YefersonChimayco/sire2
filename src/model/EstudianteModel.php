<?php
require_once "../library/conexion.php";

class EstudianteModel {
    private $conexion;
    
    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarEstudiante($data) {
        $sql = $this->conexion->query("INSERT INTO estudiantes (dni, nombres, apellido_paterno, apellido_materno, estado, semestre, programa_id, fecha_matricula) 
                                      VALUES ('{$data['dni']}', '{$data['nombres']}', '{$data['apellido_paterno']}', '{$data['apellido_materno']}', '{$data['estado']}', '{$data['semestre']}', '{$data['programa_id']}', '{$data['fecha_matricula']}')");
        return $sql;
    }

    public function actualizarEstudiante($data) {
        $sql = $this->conexion->query("UPDATE estudiantes SET 
                                      dni='{$data['dni']}', 
                                      nombres='{$data['nombres']}', 
                                      apellido_paterno='{$data['apellido_paterno']}', 
                                      apellido_materno='{$data['apellido_materno']}', 
                                      estado='{$data['estado']}', 
                                      semestre='{$data['semestre']}', 
                                      programa_id='{$data['programa_id']}', 
                                      fecha_matricula='{$data['fecha_matricula']}' 
                                      WHERE dni='{$data['dni_original']}'");
        return $sql;
    }

    public function eliminarEstudiante($dni) {
        $sql = $this->conexion->query("DELETE FROM estudiantes WHERE dni='$dni'");
        return $sql;
    }

    public function buscarEstudianteByDni($dni) {
        $sql = $this->conexion->query("SELECT * FROM estudiantes WHERE dni='$dni'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarEstudiantesOrderByApellidosNombres_tabla_filtro($busqueda_tabla_dni, $busqueda_tabla_nombres, $busqueda_tabla_estado) {
        $condicion = "1=1";
        if (!empty($busqueda_tabla_dni)) {
            $condicion .= " AND dni LIKE '$busqueda_tabla_dni%'";
        }
        if (!empty($busqueda_tabla_nombres)) {
            $condicion .= " AND (nombres LIKE '%$busqueda_tabla_nombres%' OR apellido_paterno LIKE '%$busqueda_tabla_nombres%' OR apellido_materno LIKE '%$busqueda_tabla_nombres%')";
        }
        if (!empty($busqueda_tabla_estado)) {
            $condicion .= " AND estado = '$busqueda_tabla_estado'";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM estudiantes WHERE $condicion ORDER BY apellido_paterno, apellido_materno, nombres");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarEstudiantesOrderByApellidosNombres_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_dni, $busqueda_tabla_nombres, $busqueda_tabla_estado) {
        $condicion = "1=1";
        if (!empty($busqueda_tabla_dni)) {
            $condicion .= " AND dni LIKE '$busqueda_tabla_dni%'";
        }
        if (!empty($busqueda_tabla_nombres)) {
            $condicion .= " AND (nombres LIKE '%$busqueda_tabla_nombres%' OR apellido_paterno LIKE '%$busqueda_tabla_nombres%' OR apellido_materno LIKE '%$busqueda_tabla_nombres%')";
        }
        if (!empty($busqueda_tabla_estado)) {
            $condicion .= " AND estado = '$busqueda_tabla_estado'";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM estudiantes WHERE $condicion ORDER BY apellido_paterno, apellido_materno, nombres LIMIT $iniciar, $cantidad_mostrar");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function getSemestres() {
        $semestres = [];
        $query = "SELECT id, descripcion FROM semestres_lista";
        $result = $this->conexion->query($query);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $semestres[$row['id']] = $row['descripcion'];
            }
        }
        return $semestres;
    }

    public function getProgramas() {
        $programas = [];
        $query = "SELECT id, nombre FROM programas_estudio";
        $result = $this->conexion->query($query);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $programas[$row['id']] = $row['nombre'];
            }
        }
        return $programas;
    }
}
?>