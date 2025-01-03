<?php
include_once '../version1.php';

switch ($_method) {
    case 'GET':
        if ($_authorization === $_token_get) {
            $lista = [];
            //llamamos al archivo que contiene la clase conexion
            include_once '../conexion.php';
            include_once 'modeloIndicador.php';
           // include_once 'modeloUnidadMedida.php';
            //se realiza la instancia al modelo Indicador
            $modelo = new Productos();
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
            include_once 'modeloIndicador.php';
           // include_once 'modeloUnidadMedida.php';
            //se realiza la instancia al modelo Indicador
            $modelo = new Productos();
            //se recuperan los datos RAW del body en formato JSON
            $body = json_decode(file_get_contents("php://input", true)); // json_decode -> transforma un JSON a un Objeto standar para trabajar
            // print_r($body->codigo);
            // print_r($body->nombre);
            $modelo->setId($body->id);
            $modelo->setNombre($body->nombre);
            $modelo->setCategoria_id($body->categoria_id);
            $modelo->setCategoria_nombre($body->categoria_nombre);
            $modelo->setDescripcion($body->descripcion);
            // print_r($modelo);
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
        // echo 'es un patch para habilitar un registro del id ' . $_parametroID;
        if ($_authorization == $_token_patch) {
            include_once '../conexion.php';
            include_once 'modeloIndicador.php';
            // Se instancia el modelo
            $modelo = new Productos();

            $lista = $modelo->getAll();

            $existe = 0;
            foreach ($lista as $obj) {
                // var_dump($obj['id']); // es un objeto estandar, por eso no podemos utilizar el getId()
                if ($obj['id'] == $_parametroID) {
                    $existe = 1;
                    $modelo->setActivo($obj['activo']);
                }
            }

            // var_dump($modelo);

            // echo 'Existe el id: ' . $_parametroID . ' en la base de datos? ' . $existe;

            if ($existe) {
                if (!$modelo->getActivo()) {
                    $modelo->setId($_parametroID);
                    // var_dump($modelo);
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
                //no existe
                http_response_code(404);
                echo json_encode(['error' => 'El registro de id: ' . $_parametroID . ' no existe.']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Prohibido']);
        }
        break;
    case 'DELETE':
        // echo 'es un patch para habilitar un registro del id ' . $_parametroID;
        if ($_authorization == $_token_disable) {
            include_once '../conexion.php';
            include_once 'modeloIndicador.php';
            // Se instancia el modelo
            $modelo = new Productos();

            $lista = $modelo->getAll();

            $existe = 0;
            foreach ($lista as $obj) {
                // var_dump($obj['id']); // es un objeto estandar, por eso no podemos utilizar el getId()
                if ($obj['id'] == $_parametroID) {
                    $existe = 1;
                    $modelo->setActivo($obj['activo']);
                }
            }

            // var_dump($modelo);

            // echo 'Existe el id: ' . $_parametroID . ' en la base de datos? ' . $existe;

            if ($existe) {
                if ($modelo->getActivo()) {
                    $modelo->setId($_parametroID);
                    // var_dump($modelo);
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
                //no existe
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
            // echo 'estamos updateando';
            include_once '../conexion.php';
            include_once 'modeloIndicador.php';
            $modelo = new Productos();
            $body = json_decode(file_get_contents("php://input", true)); // json_decode -> transforma un JSON a un Objeto standar para trabajar
            // echo $_parametroID;
            // var_dump($body->codigo);
            $modelo->setId($_parametroID);
            $modelo->setNombre($body->nombre);
            $modelo->setCategoria_id($body->categoria_id);
            $modelo->setCategoria_nombre($body->categoria_nombre);
            $modelo->setDescripcion($body->descripcion);

            // var_dump($modelo);
            $lista = $modelo->getAll();
            $registroBD = new Productos();

            $existe = 0;
            foreach ($lista as $obj) {
                // var_dump($obj['id']); // es un objeto estandar, por eso no podemos utilizar el getId()
                if ($obj['id'] == $_parametroID) {
                    $existe = 1;
                    $registroBD->setNombre($obj['nombre']);
                    $registroBD->setCategoria_id($obj['categoria_id']);
                    $registroBD->setCategoria_nombre($obj['categoria_nombre']);
                    $registroBD->setDescripcion($obj['descripcion']);
                }
            }

            // var_dump($registroBD);

            $cambios = 0;

            if ($registroBD->getNombre() != $modelo->getNombre()) {
                $cambios++;
            }

            if ($registroBD->getCategoria_id() != $modelo->getCategoria_id()) {
                $cambios++;
            }

            if ($registroBD->getCategoria_nombre() != $modelo->getCategoria_nombre()) {
                $cambios++;
            }
            if ($registroBD->getDescripcion() != $modelo->getDescripcion()) {
                $cambios++;
            }

            // echo 'cambios a realizar: ' . $cambios;

            if ($cambios == 0) {
                http_response_code(409);
                echo json_encode(['error' => 'No se actualizó el registro correspondiente al id: ' . $_parametroID . ' porque no hay cambios.']);
            } else {
                if ($existe) {
                    $respuesta = $modelo->update($modelo);
                    if ($respuesta) {
                        http_response_code(202);
                        echo json_encode(['Actualizado' => 'Sin errores, se realizo(ron): ' . $cambios . ' cambio(s)']);
                    } else {
                        http_response_code(416);
                        echo json_encode(['error' => 'No se actulizó el registro correspondiente al id: ' . $_parametroID]);
                    }
                } else {
                    //no existe
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
