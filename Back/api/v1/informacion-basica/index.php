<?php
include_once '../version1.php';

switch ($_method) {
    case 'GET':
        if ($_authorization === $_token_get) {
            $lista = [];
            include_once '../conexion.php';
            include_once 'informacion-basica.php';

            $modelo = new InformacionBasica();
            $lista = $modelo->getAll();

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
            include_once 'informacion-basica.php';

            $modelo = new InformacionBasica();
            $body = json_decode(file_get_contents("php://input", true));

            $modelo->setId($body->id);
            $modelo->setNombre($body->nombre);
            $modelo->setDescripcion($body->descripcion);

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
            include_once 'informacion-basica.php';

            $modelo = new InformacionBasica();
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
                        echo json_encode(['error' => 'No se habilitó el registro.']);
                    }
                } else {
                    http_response_code(409);
                    echo json_encode(['error' => 'El registro ya estaba habilitado.']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'El registro no existe.']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;

    case 'DELETE':
        if ($_authorization == $_token_disable) {
            include_once '../conexion.php';
            include_once 'informacion-basica.php';

            $modelo = new InformacionBasica();
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
                        echo json_encode(['Deshabilitado' => 'Sin errores']);
                    } else {
                        http_response_code(416);
                        echo json_encode(['error' => 'No se deshabilitó el registro.']);
                    }
                } else {
                    http_response_code(409);
                    echo json_encode(['error' => 'El registro ya estaba deshabilitado.']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'El registro no existe.']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;

    case 'PUT':
        if ($_authorization == $_token_put) {
            include_once '../conexion.php';
            include_once 'informacion-basica.php';

            $modelo = new InformacionBasica();
            $body = json_decode(file_get_contents("php://input", true));

            $modelo->setId($_parametroID);
            $modelo->setNombre($body->nombre);
            $modelo->setDescripcion($body->descripcion);

            $lista = $modelo->getAll();
            $registroBD = new InformacionBasica();

            $existe = 0;
            foreach ($lista as $obj) {
                if ($obj['id'] == $_parametroID) {
                    $existe = 1;
                    $registroBD->setNombre($obj['nombre']);
                    $registroBD->setDescripcion($obj['descripcion']);
                }
            }

            $cambios = 0;

            if ($registroBD->getNombre() != $modelo->getNombre()) {
                $cambios++;
            }

            if ($registroBD->getDescripcion() != $modelo->getDescripcion()) {
                $cambios++;
            }

            if ($cambios == 0) {
                http_response_code(409);
                echo json_encode(['error' => 'No hay cambios en el registro.']);
            } else {
                if ($existe) {
                    $respuesta = $modelo->update($modelo);
                    if ($respuesta) {
                        http_response_code(202);
                        echo json_encode(['Actualizado' => 'Sin errores, cambios realizados: ' . $cambios]);
                    } else {
                        http_response_code(416);
                        echo json_encode(['error' => 'No se actualizó el registro.']);
                    }
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'El registro no existe.']);
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


