<?php
session_start();
require_once('../model/admin-sesionModel.php');
require_once('../model/SemestreModel.php');

$objSesion = new SessionModel();
$objSemestre = new SemestreModel();

$tipo = $_GET['tipo'] ?? '';

// Variables de sesión (igual que programas y estudiantes)
$id_sesion = $_POST['sesion'] ?? '';
$token = $_POST['token'] ?? '';

if ($tipo == "listar_semestres_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $pagina = $_POST['pagina'] ?? 1;
        $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
        $busqueda_tabla_descripcion = $_POST['busqueda_tabla_descripcion'] ?? '';

        $arr_Respuesta = array('status' => false, 'contenido' => '');
        $busqueda_filtro = $objSemestre->buscarSemestresOrderByDescripcion_tabla_filtro($busqueda_tabla_descripcion);
        $arr_Semestres = $objSemestre->buscarSemestresOrderByDescripcion_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_descripcion);
        
        $arr_contenido = [];
        if (!empty($arr_Semestres)) {
            for ($i = 0; $i < count($arr_Semestres); $i++) {
                $arr_contenido[$i] = (object) [];
                $arr_contenido[$i]->id = $arr_Semestres[$i]->id;
                $arr_contenido[$i]->descripcion = $arr_Semestres[$i]->descripcion;
                
                $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Semestres[$i]->id . '"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger waves-effect waves-light" title="Eliminar" onclick="eliminar_semestre(' . $arr_Semestres[$i]->id . ')"><i class="fa fa-trash"></i></button>';
                $arr_contenido[$i]->options = $opciones;
            }
            $arr_Respuesta['total'] = count($busqueda_filtro);
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "registrar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $descripcion = $_POST['descripcion'] ?? '';

            if ($descripcion == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, descripción vacía');
            } else {
                $arr_Semestre = $objSemestre->buscarSemestreByDescripcion($descripcion);
                if ($arr_Semestre) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, Semestre ya se encuentra registrado');
                } else {
                    $success = $objSemestre->registrarSemestre($descripcion);
                    if ($success) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Semestre registrado exitosamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar semestre');
                    }
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "actualizar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id = $_POST['data'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            if ($id == "" || $descripcion == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Semestre = $objSemestre->buscarSemestreByDescripcion($descripcion);
                if ($arr_Semestre && $arr_Semestre->id != $id) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Descripción ya está registrada');
                    echo json_encode($arr_Respuesta);
                    return;
                }
                
                $success = $objSemestre->actualizarSemestre($id, $descripcion);
                if ($success) {
                    $arr_Respuesta = array('status' => true, 'mensaje' => 'Semestre actualizado correctamente');
                } else {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar semestre');
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "eliminar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $arr_Respuesta = array('status' => false, 'mensaje' => 'ID no válido');
        } else {
            $success = $objSemestre->eliminarSemestre($id);
            if ($success) {
                $arr_Respuesta = array('status' => true, 'mensaje' => 'Semestre eliminado correctamente');
            } else {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al eliminar semestre');
            }
        }
    }
    echo json_encode($arr_Respuesta);
}
?>