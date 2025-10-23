<?php
session_start();
require_once('../model/admin-sesionModel.php');
require_once('../model/ClienteModel.php');

$objSesion = new SessionModel();
$objCliente = new ClienteModel();

$tipo = $_GET['tipo'] ?? '';

// Variables de sesión
$id_sesion = $_POST['sesion'] ?? '';
$token = $_POST['token'] ?? '';

if ($tipo == "listar_clientes_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $pagina = intval($_POST['pagina'] ?? 1);
        $cantidad_mostrar = intval($_POST['cantidad_mostrar'] ?? 10);
        $busqueda_tabla_ruc = $_POST['busqueda_tabla_ruc'] ?? '';
        $busqueda_tabla_razon_social = $_POST['busqueda_tabla_razon_social'] ?? '';
        $busqueda_tabla_estado = $_POST['busqueda_tabla_estado'] ?? '';

        // Obtener datos PAGINADOS
        $arr_Clientes = $objCliente->buscarClientesOrderByRazonSocial_tabla(
            $pagina, 
            $cantidad_mostrar, 
            $busqueda_tabla_ruc, 
            $busqueda_tabla_razon_social, 
            $busqueda_tabla_estado
        );
        
        // Obtener total para paginación - USANDO EL MÉTODO CORREGIDO
        $total_clientes = $objCliente->contarClientesFiltro(
            $busqueda_tabla_ruc, 
            $busqueda_tabla_razon_social, 
            $busqueda_tabla_estado
        );
        
        $arr_contenido = [];
        if (!empty($arr_Clientes)) {
            $contador = ($pagina - 1) * $cantidad_mostrar;
            
            foreach ($arr_Clientes as $cliente) {
                $contador++;
                $arr_item = array();
                $arr_item['nro'] = $contador;
                $arr_item['id'] = $cliente->id ?? '';
                $arr_item['ruc'] = $cliente->ruc ?? 'N/A';
                $arr_item['razon_social'] = $cliente->razon_social ?? 'N/A';
                $arr_item['telefono'] = $cliente->telefono ?? '-';
                $arr_item['correo'] = $cliente->correo ?? '-';
                $arr_item['fecha_registro'] = $cliente->fecha_registro ?? 'N/A';
                $arr_item['estado'] = $cliente->estado ?? '0';
                
                // Botones de acciones
                $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar_' . ($cliente->id ?? '') . '"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger waves-effect waves-light" title="Eliminar" onclick="eliminar_cliente(\'' . ($cliente->id ?? '') . '\')"><i class="fa fa-trash"></i></button>';
                $arr_item['opciones'] = $opciones;
                
                $arr_contenido[] = $arr_item;
            }
            
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
            $arr_Respuesta['total'] = $total_clientes; // Usar el total contado
        } else {
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = [];
            $arr_Respuesta['total'] = 0;
            $arr_Respuesta['msg'] = 'No se encontraron clientes';
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($arr_Respuesta);
    exit;
}

if ($tipo == "registrar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $ruc = trim($_POST['ruc'] ?? '');
            $razon_social = trim($_POST['razon_social'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $fecha_registro = $_POST['fecha_registro'] ?? date('Y-m-d');
            $estado = $_POST['estado'] ?? '1';

            // Validaciones
            if (empty($ruc) || empty($razon_social)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error: RUC y Razón Social son obligatorios');
            } else {
                // Verificar si el RUC ya existe
                $cliente_existente = $objCliente->buscarClienteByRuc($ruc);
                if ($cliente_existente) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Error: El RUC ya está registrado');
                } else {
                    $data = [
                        'ruc' => $ruc,
                        'razon_social' => $razon_social,
                        'telefono' => $telefono,
                        'correo' => $correo,
                        'fecha_registro' => $fecha_registro,
                        'estado' => $estado
                    ];
                    
                    $resultado = $objCliente->registrarCliente($data);
                    if ($resultado) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Cliente registrado exitosamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar el cliente');
                    }
                }
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($arr_Respuesta);
    exit;
}

if ($tipo == "actualizar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id_original = intval($_POST['data'] ?? 0);
            $ruc = trim($_POST['ruc'] ?? '');
            $razon_social = trim($_POST['razon_social'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $fecha_registro = $_POST['fecha_registro'] ?? '';
            $estado = $_POST['estado'] ?? '1';

            if (empty($ruc) || empty($razon_social)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error: RUC y Razón Social son obligatorios');
            } elseif ($id_original <= 0) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error: ID de cliente no válido');
            } else {
                // Verificar si el RUC ya existe en otro cliente
                $cliente_existente = $objCliente->buscarClienteByRuc($ruc);
                if ($cliente_existente && $cliente_existente->id != $id_original) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Error: El RUC ya está registrado en otro cliente');
                } else {
                    $data = [
                        'id_original' => $id_original,
                        'ruc' => $ruc,
                        'razon_social' => $razon_social,
                        'telefono' => $telefono,
                        'correo' => $correo,
                        'fecha_registro' => $fecha_registro,
                        'estado' => $estado
                    ];
                    
                    $resultado = $objCliente->actualizarCliente($data);
                    if ($resultado) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Cliente actualizado correctamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar el cliente');
                    }
                }
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($arr_Respuesta);
    exit;
}

if ($tipo == "eliminar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $id = intval($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error: ID no válido');
        } else {
            $resultado = $objCliente->eliminarCliente($id);
            if ($resultado) {
                $arr_Respuesta = array('status' => true, 'mensaje' => 'Cliente eliminado correctamente');
            } else {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al eliminar el cliente');
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($arr_Respuesta);
    exit;
}

// Si no se reconoce el tipo
$arr_Respuesta = array('status' => false, 'mensaje' => 'Tipo de acción no reconocido');
header('Content-Type: application/json');
echo json_encode($arr_Respuesta);
exit;
?>