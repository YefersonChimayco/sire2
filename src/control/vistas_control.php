<?php

require_once "./src/model/vistas_model.php";

class vistasControlador extends vistaModelo
{
    public function obtenerPlantillaControlador()
    {
        return require_once "./src/view/plantilla.php";
    }
    
    public function obtenerVistaControlador()
    {
        // Verificar si es una ruta de API (acceso público)
        if (isset($_GET['views'])) {
            $ruta = explode("/", $_GET['views']);
            
            // Rutas públicas que NO requieren login
            if ($ruta[0] == "apiestudiante" || $ruta[0] == "reset-password") {
                $respuesta = vistaModelo::obtener_vista($ruta[0]);
                return $respuesta;
            }
        }

        // Si no es ruta pública, verificar sesión
        if (!isset($_SESSION['sesion_id'])) {
            if (isset($_GET['views'])) {
                $ruta = explode("/", $_GET['views']);
                if ($ruta[0] == "reset-password") {
                    $respuesta = "reset-password";
                } else {
                    $respuesta = "login";
                }
            } else {
                $respuesta = "login";
            }
        } else {
            if (isset($_GET['views'])) {
                $ruta = explode("/", $_GET['views']);
                $respuesta = vistaModelo::obtener_vista($ruta[0]);
            } else {
                $respuesta = "inicio.php";
            }
        }
        return $respuesta;
    }
}