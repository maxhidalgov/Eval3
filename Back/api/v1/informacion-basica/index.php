<?php
include_once '../version1.php';

switch ($_method) {
    case 'GET':
        if ($_authorization === $_token_get) {
            include_once '../conexion.php';
            include_once 'informacion-basica.php';

            $modelo = new InformacionBasica();
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
            include_once 'informacion-basica.php';

            $body = json_decode(file_get_contents("php://input"), true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($body['id'], $body['nombre'], $body['descripcion'])) {
                http_response_code(400);
                echo json_encode(['error' => 'JSON inválido o faltan campos obligatorios']);
                exit;
            }

            $modelo = new InformacionBasica();
            $modelo->setId($body['id']);
            $modelo->setNombre($body['nombre']);
            $modelo->setDescripcion($body['descripcion']);

            $respuesta = $modelo->add($modelo);
            if ($respuesta) {
                http_response_code(201);
                echo json_encode(['Creado' => 'Sin errores']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'No se pudo crear el registro']);
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
                // Se instancia el modelo
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
                include_once 'informacion-basica.php';
                // Se instancia el modelo
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
                include_once 'informacion-basica.php';
                $modelo = new InformacionBasica();
                $body = json_decode(file_get_contents("php://input", true)); // json_decode -> transforma un JSON a un Objeto estándar para trabajar
    
                $modelo->setId($_parametroID);
                $modelo->setLogoUrl($body->logo_url);
                $modelo->setNombreEmpresa($body->nombre_empresa);
                $modelo->setDescripcion($body->descripcion);
                $modelo->setPalabrasClave($body->palabras_clave);
                $modelo->setMenuPrincipal($body->menu_principal);

                
                $lista = $modelo->getAll();
                $registroBD = new InformacionBasica();
    
                $existe = 0;
                foreach ($lista as $obj) {
                    if ($obj['id'] == $_parametroID) {
                        $existe = 1;
                        $registroBD->setLogoUrl($obj['logo_url']);
                        $registroBD->setNombreEmpresa($obj['nombre_empresa']);
                        $registroBD->setDescripcion($obj['descripcion']);
                        $registroBD->setPalabrasClave($obj['palabras_clave']);
                        $registroBD->setMenuPrincipal($obj['menu_principal']);
                    }
                }
    
                $cambios = 0;
    
                if ($registroBD->getLogoUrl() != $modelo->getLogoUrl()) {
                    $cambios++;
                }
    
                if ($registroBD->getNombreEmpresa() != $modelo->getNombreEmpresa()) {
                    $cambios++;
                }
    
                if ($registroBD->getDescripcion() != $modelo->getDescripcion()) {
                    $cambios++;
                }
    
                if ($registroBD->getPalabrasClave() != $modelo->getPalabrasClave()) {
                    $cambios++;
                }
                    
                if ($registroBD->getMenuPrincipal() != $modelo->getMenuPrincipal()) {
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
