<?php
include_once '../version1.php';

switch ($_method) {
    case 'GET':
        if ($_authorization === $_token_get) {
            $lista = [];
            // Llamamos al archivo que contiene la clase conexion
            include_once '../conexion.php';
            include_once 'quienes-somos.php';  // Modelo para la sección "Quiénes Somos"
            
            // Se realiza la instancia al modelo QuienesSomos
            $modelo = new QuienesSomos();
            $lista = $modelo->get();  // Obtener la información de "Quiénes Somos"

            http_response_code(200);
            echo json_encode(['data' => $lista]);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;
    
    case 'POST':
        if ($_authorization === $_token_post) {
            include_once '../conexion.php';
            include_once 'modeloQuienesSomos.php';  // Modelo para la sección "Quiénes Somos"
            
            // Se realiza la instancia al modelo QuienesSomos
            $modelo = new QuienesSomos();
            
            // Se recuperan los datos RAW del body en formato JSON
            $body = json_decode(file_get_contents("php://input"), true);
            
            $modelo->setTitulo($body['titulo']);
            $modelo->setDescripcion($body['descripcion']);
            $modelo->setMision($body['mision']);
            $modelo->setVision($body['vision']);
          //  $modelo->setValores($body['valores']);
            
            // Agregar la nueva información de la sección "Quiénes Somos"
            $respuesta = $modelo->add($modelo);
            if ($respuesta) {
                http_response_code(201);
                echo json_encode(['Creado' => 'Sin errores']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;
    
    case 'PATCH':
        if ($_authorization === $_token_patch) {
            include_once '../conexion.php';
            include_once 'modeloQuienesSomos.php';  // Modelo para la sección "Quiénes Somos"
            
            // Se instancia el modelo
            $modelo = new QuienesSomos();
            
            $body = json_decode(file_get_contents("php://input"), true);
            
            $modelo->setTitulo($body['titulo']);
            $modelo->setDescripcion($body['descripcion']);
            $modelo->setMision($body['mision']);
            $modelo->setVision($body['vision']);
            //$modelo->setValores($body['valores']);
            
            // Actualizar la información de la sección "Quiénes Somos"
            $respuesta = $modelo->update($modelo);
            if ($respuesta) {
                http_response_code(202);
                echo json_encode(['Actualizado' => 'Sin errores']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'No se actualizó la sección.']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;

    case 'DELETE':
        if ($_authorization === $_token_disable) {
            include_once '../conexion.php';
            include_once 'modeloQuienesSomos.php';  // Modelo para la sección "Quiénes Somos"
            
            // Se instancia el modelo
            $modelo = new QuienesSomos();
            
            // Eliminar la sección "Quiénes Somos"
            $respuesta = $modelo->delete();
            if ($respuesta) {
                http_response_code(202);
                echo json_encode(['Eliminado' => 'Sin errores']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No se eliminó la sección.']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;

    case 'PUT':
        if ($_authorization === $_token_put) {
            include_once '../conexion.php';
            include_once 'quienes-somos.php';  // Modelo para la sección "Quiénes Somos"
            
            $modelo = new QuienesSomos();
            
            $body = json_decode(file_get_contents("php://input"), true);
            
            $modelo->setTitulo($body['titulo']);
            $modelo->setDescripcion($body['descripcion']);
            $modelo->setMision($body['mision']);
            $modelo->setVision($body['vision']);
          //  $modelo->setValores($body['valores']);
            
            // Actualizar la información de la sección "Quiénes Somos"
            $respuesta = $modelo->update($modelo);
            if ($respuesta) {
                http_response_code(202);
                echo json_encode(['Actualizado' => 'Sin errores']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'No se actualizó la sección.']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;

    default:
        http_response_code(501);
        echo json_encode(['error' => 'No implementado']);
        break;
}


