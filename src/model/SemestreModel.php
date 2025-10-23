<?php
require_once "../library/conexion.php";

class SemestreModel {
    private $conexion;
    
    function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarSemestre($descripcion) {
        $sql = $this->conexion->query("INSERT INTO semestres_lista (descripcion) VALUES ('$descripcion')");
        return $sql;
    }

    public function actualizarSemestre($id, $descripcion) {
        $sql = $this->conexion->query("UPDATE semestres_lista SET descripcion='$descripcion' WHERE id='$id'");
        return $sql;
    }

    public function eliminarSemestre($id) {
        $sql = $this->conexion->query("DELETE FROM semestres_lista WHERE id='$id'");
        return $sql;
    }

    public function buscarSemestreById($id) {
        $sql = $this->conexion->query("SELECT * FROM semestres_lista WHERE id='$id'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarSemestreByDescripcion($descripcion) {
        $sql = $this->conexion->query("SELECT * FROM semestres_lista WHERE descripcion='$descripcion'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarSemestresOrderByDescripcion_tabla_filtro($busqueda_tabla_descripcion) {
        $condicion = "1=1";
        if (!empty($busqueda_tabla_descripcion)) {
            $condicion .= " AND descripcion LIKE '%$busqueda_tabla_descripcion%'";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM semestres_lista WHERE $condicion ORDER BY descripcion");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarSemestresOrderByDescripcion_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_descripcion) {
        $condicion = "1=1";
        if (!empty($busqueda_tabla_descripcion)) {
            $condicion .= " AND descripcion LIKE '%$busqueda_tabla_descripcion%'";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM semestres_lista WHERE $condicion ORDER BY descripcion LIMIT $iniciar, $cantidad_mostrar");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function getAllSemestres() {
        $arrRespuesta = array();
        $sql = $this->conexion->query("SELECT * FROM semestres_lista ORDER BY descripcion");
        while ($objeto = $sql->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }
}
?>