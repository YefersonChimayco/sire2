<?php
session_start();
require_once('../model/admin-sesionModel.php');
require_once('../model/ProgramaModel.php');

$objSesion = new SessionModel();
$objPrograma = new ProgramaModel();

$tipo = $_GET['tipo'] ?? '';

// Variables de sesión (igual que estudiantes)
$id_sesion = $_POST['sesion'] ?? '';
$token = $_POST['token'] ?? '';

if ($tipo == "listar_programas_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $pagina = $_POST['pagina'] ?? 1;
        $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
        $busqueda_tabla_nombre = $_POST['busqueda_tabla_nombre'] ?? '';

        $arr_Respuesta = array('status' => false, 'contenido' => '');
        $busqueda_filtro = $objPrograma->buscarProgramasOrderByNombre_tabla_filtro($busqueda_tabla_nombre);
        $arr_Programas = $objPrograma->buscarProgramasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_nombre);
        
        $arr_contenido = [];
        if (!empty($arr_Programas)) {
            for ($i = 0; $i < count($arr_Programas); $i++) {
                $arr_contenido[$i] = (object) [];
                $arr_contenido[$i]->id = $arr_Programas[$i]->id;
                $arr_contenido[$i]->nombre = $arr_Programas[$i]->nombre;
                $arr_contenido[$i]->descripcion = $arr_Programas[$i]->descripcion;
                
                $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Programas[$i]->id . '"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger waves-effect waves-light" title="Eliminar" onclick="eliminar_programa(' . $arr_Programas[$i]->id . ')"><i class="fa fa-trash"></i></button>';
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
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            if ($nombre == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, nombre vacío');
            } else {
                $arr_Programa = $objPrograma->buscarProgramaByNombre($nombre);
                if ($arr_Programa) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, Programa ya se encuentra registrado');
                } else {
                    $success = $objPrograma->registrarPrograma($nombre, $descripcion);
                    if ($success) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Programa registrado exitosamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar programa');
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
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            if ($id == "" || $nombre == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Programa = $objPrograma->buscarProgramaByNombre($nombre);
                if ($arr_Programa && $arr_Programa->id != $id) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Nombre ya está registrado');
                    echo json_encode($arr_Respuesta);
                    return;
                }
                
                $success = $objPrograma->actualizarPrograma($id, $nombre, $descripcion);
                if ($success) {
                    $arr_Respuesta = array('status' => true, 'mensaje' => 'Programa actualizado correctamente');
                } else {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar programa');
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
            $success = $objPrograma->eliminarPrograma($id);
            if ($success) {
                $arr_Respuesta = array('status' => true, 'mensaje' => 'Programa eliminado correctamente');
            } else {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al eliminar programa');
            }
        }
    }
    echo json_encode($arr_Respuesta);
}
?>