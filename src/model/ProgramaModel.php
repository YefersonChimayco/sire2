<?php
require_once "../library/conexion.php";

class ProgramaModel {
    private $conexion;
    
    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarPrograma($nombre, $descripcion) {
        $sql = $this->conexion->query("INSERT INTO programas_estudio (nombre, descripcion) VALUES ('$nombre', '$descripcion')");
        return $sql;
    }

    public function actualizarPrograma($id, $nombre, $descripcion) {
        $sql = $this->conexion->query("UPDATE programas_estudio SET nombre='$nombre', descripcion='$descripcion' WHERE id='$id'");
        return $sql;
    }

    public function eliminarPrograma($id) {
        $sql = $this->conexion->query("DELETE FROM programas_estudio WHERE id='$id'");
        return $sql;
    }

    public function buscarProgramaById($id) {
        $sql = $this->conexion->query("SELECT * FROM programas_estudio WHERE id='$id'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarProgramaByNombre($nombre) {
        $sql = $this->conexion->query("SELECT * FROM programas_estudio WHERE nombre='$nombre'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarProgramasOrderByNombre_tabla_filtro($busqueda_tabla_nombre) {
        $condicion = "1=1";
        if (!empty($busqueda_tabla_nombre)) {
            $condicion .= " AND nombre LIKE '%$busqueda_tabla_nombre%'";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM programas_estudio WHERE $condicion ORDER BY nombre");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarProgramasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_nombre) {
        $condicion = "1=1";
        if (!empty($busqueda_tabla_nombre)) {
            $condicion .= " AND nombre LIKE '%$busqueda_tabla_nombre%'";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM programas_estudio WHERE $condicion ORDER BY nombre LIMIT $iniciar, $cantidad_mostrar");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function getAllProgramas() {
        $arrRespuesta = array();
        $sql = $this->conexion->query("SELECT * FROM programas_estudio ORDER BY nombre");
        while ($objeto = $sql->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }
}
?>