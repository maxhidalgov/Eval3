<?php
include_once '../version1.php';

switch ($_method) {
    case 'GET':
        if ($_authorization === $_token_get) {
            include_once '../conexion.php';
            include_once 'estilo-visual.php';

            $modelo = new EstiloVisual();
            $lista = $modelo->getAll();
        //    var_dump($lista); die;

            if (is_array($lista)) {
                http_response_code(200);
                echo json_encode(['data' => $lista]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al obtener la lista']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;

        case 'POST':
            if ($_authorization === $_token_post) {
                include_once '../conexion.php';
                include_once 'estilo-visual.php';
                //se realiza la instancia al modelo EstiloVisual
                $modelo = new EstiloVisual();
                //se recuperan los datos RAW del body en formato JSON
                $body = json_decode(file_get_contents("php://input", true)); // json_decode -> transforma un JSON a un Objeto estándar para trabajar
                $modelo->setId($body->id);
                $modelo->setNombre($body->nombre);
                $modelo->setColor($body->color);
                $modelo->setDescripcion($body->descripcion);
                //agrega el nuevo valor
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
            if ($_authorization == $_token_patch) {
                include_once '../conexion.php';
                include_once 'estilo-visual.php';
                // Se instancia el modelo
                $modelo = new EstiloVisual();
    
                $lista = $modelo->getAll();
    
                $existe = 0;
                foreach ($lista as $obj) {
                    if ($obj['id'] == $_parametroID) {
                        $existe = 1;
                        $modelo->setActivo($obj['activo']);
                    }
                }
    
                if ($existe) {
                    if (!$modelo->getActivo()) {
                        $modelo->setId($_parametroID);
                        $respuesta = $modelo->enable($modelo);
                        if ($respuesta) {
                            http_response_code(202);
                            echo json_encode(['Habilitado' => 'Sin errores']);
                        } else {
                            http_response_code(416);
                            echo json_encode(['error' => 'No se habilitó el registro correspondiente al id: ' . $_parametroID]);
                        }
                    } else {
                        http_response_code(409);
                        echo json_encode(['error' => 'No se habilitó el registro correspondiente al id: ' . $_parametroID . ' porque ya estaba habilitado.']);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'El registro de id: ' . $_parametroID . ' no existe.']);
                }
            } else {
                http_response_code(403);
                echo json_encode(['error' => 'Prohibido']);
            }
            break;

        case 'DELETE':
            if ($_authorization == $_token_disable) {
                include_once '../conexion.php';
                include_once 'estilo-visual.php';
                // Se instancia el modelo
                $modelo = new EstiloVisual();
    
                $lista = $modelo->getAll();
    
                $existe = 0;
                foreach ($lista as $obj) {
                    if ($obj['id'] == $_parametroID) {
                        $existe = 1;
                        $modelo->setActivo($obj['activo']);
                    }
                }
    
                if ($existe) {
                    if ($modelo->getActivo()) {
                        $modelo->setId($_parametroID);
                        $respuesta = $modelo->disable($modelo);
                        if ($respuesta) {
                            http_response_code(202);
                            echo json_encode(['deshabilitado' => 'Sin errores']);
                        } else {
                            http_response_code(416);
                            echo json_encode(['error' => 'No se deshabilitó el registro correspondiente al id: ' . $_parametroID]);
                        }
                    } else {
                        http_response_code(409);
                        echo json_encode(['error' => 'No se deshabilitó el registro correspondiente al id: ' . $_parametroID . ' porque ya estaba deshabilitado.']);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'El registro de id: ' . $_parametroID . ' no existe.']);
                }
            } else {
                http_response_code(403);
                echo json_encode(['error' => 'Prohibido']);
            }
            break;


  

        case 'PUT':
            if ($_authorization == $_token_put) {
                include_once '../conexion.php';
                include_once 'estilo-visual.php';
                $modelo = new EstiloVisual();
                $body = json_decode(file_get_contents("php://input", true)); // json_decode -> transforma un JSON a un Objeto estándar para trabajar
    
                $modelo->setId($_parametroID);
                $modelo->setNombre($body->nombre);
                $modelo->setColor($body->color);
                $modelo->setDescripcion($body->descripcion);
                
                $lista = $modelo->getAll();
                $registroBD = new EstiloVisual();
    
                $existe = 0;
                foreach ($lista as $obj) {
                    if ($obj['id'] == $_parametroID) {
                        $existe = 1;
                        $registroBD->setNombre($obj['nombre']);
                        $registroBD->setColor($obj['color']);
                        $registroBD->setDescripcion($obj['descripcion']);
                    }
                }
    
                $cambios = 0;
    
                if ($registroBD->getNombre() != $modelo->getNombre()) {
                    $cambios++;
                }
    
                if ($registroBD->getColor() != $modelo->getColor()) {
                    $cambios++;
                }
    
                if ($registroBD->getDescripcion() != $modelo->getDescripcion()) {
                    $cambios++;
                }

    
                if ($cambios == 0) {
                    http_response_code(409);
                    echo json_encode(['error' => 'No se actualizó el registro correspondiente al id: ' . $_parametroID . ' porque no hay cambios.']);
                } else {
                    if ($existe) {
                        $respuesta = $modelo->update($modelo);
                        if ($respuesta) {
                            http_response_code(202);
                            echo json_encode(['Actualizado' => 'Sin errores, se realizó(ron): ' . $cambios . ' cambio(s)']);
                        } else {
                            http_response_code(416);
                            echo json_encode(['error' => 'No se actualizó el registro correspondiente al id: ' . $_parametroID]);
                        }
                    } else {
                        http_response_code(404);
                        echo json_encode(['error' => 'El registro de id: ' . $_parametroID . ' no existe.']);
                    }
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
