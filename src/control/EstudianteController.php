<?php
session_start();
require_once('../model/admin-sesionModel.php');
require_once('../model/EstudianteModel.php');

$objSesion = new SessionModel();
$objEstudiante = new EstudianteModel();

$tipo = $_GET['tipo'] ?? '';

// Variables de sesión (igual que usuarios)
$id_sesion = $_POST['sesion'] ?? '';
$token = $_POST['token'] ?? '';

if ($tipo == "listar_estudiantes_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $pagina = $_POST['pagina'] ?? 1;
        $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
        $busqueda_tabla_dni = $_POST['busqueda_tabla_dni'] ?? '';
        $busqueda_tabla_nombres = $_POST['busqueda_tabla_nombres'] ?? '';
        $busqueda_tabla_estado = $_POST['busqueda_tabla_estado'] ?? '';

        $arr_Respuesta = array('status' => false, 'contenido' => '');
        $busqueda_filtro = $objEstudiante->buscarEstudiantesOrderByApellidosNombres_tabla_filtro($busqueda_tabla_dni, $busqueda_tabla_nombres, $busqueda_tabla_estado);
        $arr_Estudiantes = $objEstudiante->buscarEstudiantesOrderByApellidosNombres_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_dni, $busqueda_tabla_nombres, $busqueda_tabla_estado);
        
        $arr_contenido = [];
        if (!empty($arr_Estudiantes)) {
            for ($i = 0; $i < count($arr_Estudiantes); $i++) {
                $arr_contenido[$i] = (object) [];
                $arr_contenido[$i]->dni = $arr_Estudiantes[$i]->dni;
                $arr_contenido[$i]->nombres = $arr_Estudiantes[$i]->nombres;
                $arr_contenido[$i]->apellido_paterno = $arr_Estudiantes[$i]->apellido_paterno;
                $arr_contenido[$i]->apellido_materno = $arr_Estudiantes[$i]->apellido_materno;
                $arr_contenido[$i]->estado = $arr_Estudiantes[$i]->estado;
                $arr_contenido[$i]->semestre = $arr_Estudiantes[$i]->semestre;
                $arr_contenido[$i]->programa_id = $arr_Estudiantes[$i]->programa_id;
                $arr_contenido[$i]->programa_nombre = $arr_Estudiantes[$i]->programa_nombre ?? 'No asignado';
                $arr_contenido[$i]->fecha_matricula = $arr_Estudiantes[$i]->fecha_matricula;
                
                $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Estudiantes[$i]->dni . '"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger waves-effect waves-light" title="Eliminar" onclick="eliminar_estudiante(\'' . $arr_Estudiantes[$i]->dni . '\')"><i class="fa fa-trash"></i></button>';
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
            $dni = $_POST['dni'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $apellido_paterno = $_POST['apellido_paterno'] ?? '';
            $apellido_materno = $_POST['apellido_materno'] ?? '';
            $estado = $_POST['estado'] ?? 'activo';
            $semestre = $_POST['semestre'] ?? 1;
            $programa_id = $_POST['programa_id'] ?? 1;
            $fecha_matricula = $_POST['fecha_matricula'] ?? date('Y-m-d');

            if ($dni == "" || $nombres == "" || $apellido_paterno == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Estudiante = $objEstudiante->buscarEstudianteByDni($dni);
                if ($arr_Estudiante) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, Estudiante ya se encuentra registrado');
                } else {
                    $data = [
                        'dni' => $dni,
                        'nombres' => $nombres,
                        'apellido_paterno' => $apellido_paterno,
                        'apellido_materno' => $apellido_materno,
                        'estado' => $estado,
                        'semestre' => $semestre,
                        'programa_id' => $programa_id,
                        'fecha_matricula' => $fecha_matricula
                    ];
                    
                    $success = $objEstudiante->registrarEstudiante($data);
                    if ($success) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Estudiante registrado exitosamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar estudiante');
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
            $dni_original = $_POST['data'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $apellido_paterno = $_POST['apellido_paterno'] ?? '';
            $apellido_materno = $_POST['apellido_materno'] ?? '';
            $estado = $_POST['estado'] ?? 'activo';
            $semestre = $_POST['semestre'] ?? 1;
            $programa_id = $_POST['programa_id'] ?? 1;
            $fecha_matricula = $_POST['fecha_matricula'] ?? '';

            if ($dni == "" || $nombres == "" || $apellido_paterno == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                if ($dni_original != $dni) {
                    $arr_Estudiante = $objEstudiante->buscarEstudianteByDni($dni);
                    if ($arr_Estudiante) {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'DNI ya está registrado');
                        echo json_encode($arr_Respuesta);
                        return;
                    }
                }
                
                $data = [
                    'dni_original' => $dni_original,
                    'dni' => $dni,
                    'nombres' => $nombres,
                    'apellido_paterno' => $apellido_paterno,
                    'apellido_materno' => $apellido_materno,
                    'estado' => $estado,
                    'semestre' => $semestre,
                    'programa_id' => $programa_id,
                    'fecha_matricula' => $fecha_matricula
                ];
                
                $success = $objEstudiante->actualizarEstudiante($data);
                if ($success) {
                    $arr_Respuesta = array('status' => true, 'mensaje' => 'Estudiante actualizado correctamente');
                } else {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar estudiante');
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "eliminar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $dni = $_POST['dni'] ?? '';
        
        if (empty($dni)) {
            $arr_Respuesta = array('status' => false, 'mensaje' => 'DNI no válido');
        } else {
            $success = $objEstudiante->eliminarEstudiante($dni);
            if ($success) {
                $arr_Respuesta = array('status' => true, 'mensaje' => 'Estudiante eliminado correctamente');
            } else {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al eliminar estudiante');
            }
        }
    }
    echo json_encode($arr_Respuesta);
}
?>