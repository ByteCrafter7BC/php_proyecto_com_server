<?php
/**
* marcas1_controlador.php
*
* Derechos de autor (C) 2000-2025 ByteCrafter7BC <bytecrafter7bc@gmail.com>
*
* Este programa es software libre: puede redistribuirlo y/o modificarlo
* bajo los términos de la Licencia Pública General GNU publicada por
* la Free Software Foundation, ya sea la versión 3 de la Licencia, o
* (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil,
* pero SIN NINGUNA GARANTÍA; sin siquiera la garantía implícita de
* COMERCIABILIDAD o IDONEIDAD PARA UN PROPÓSITO PARTICULAR. Consulte la
* Licencia Pública General de GNU para obtener más detalles.
*
* Debería haber recibido una copia de la Licencia Pública General de GNU
* junto con este programa. Si no es así, consulte
* <https://www.gnu.org/licenses/>.
*/

include_once 'app\biblioteca\fabrica_dao.inc.php';
include_once 'app\prog\funciones.inc.php';

header('Content-Type: application/xml; charset=Windows-1252');

if (!isset($lcModulo) or !isset($lcMetodo) or !validar($lcModulo, $lcMetodo)) {
    header($_SERVER['SERVER_PROTOCOL'] . " 418 I'm a teapot", true, 418);
    die();
}

$lcMetodoSolicitud = $_SERVER['REQUEST_METHOD'];
$lcRespuesta = '';
$lnCodRespHttp = 200;    // 200 OK

switch ($lcMetodoSolicitud) {
    case 'GET':
        switch ($lcMetodo) {
            case 'existe-codigo':
                $lcRespuesta = obtener_dao()->existe_codigo((int) $lcParametro);
                break;
            case 'existe-nombre':
                $lcRespuesta = obtener_dao()->existe_nombre($lcParametro);
                break;
            case 'esta-vigente':
                $lcRespuesta = obtener_dao()->esta_vigente((int) $lcParametro);
                break;
            case 'esta-relacionado':
                $lcRespuesta =
                    obtener_dao()->esta_relacionado((int) $lcParametro);
                break;
            case 'contar':
                $lcRespuesta = obtener_dao()->contar($lcParametro);
                break;
            case 'obtener-nuevo-codigo':
                $lcRespuesta = obtener_dao()->obtener_nuevo_codigo();
                break;
            case 'obtener-por-codigo':
                $lcRespuesta =
                    obtener_dao()->obtener_por_codigo((int) $lcParametro);
                break;
            case 'obtener-por-nombre':
                $lcRespuesta = obtener_dao()->obtener_por_nombre($lcParametro);
                break;
            case 'obtener-todos':
                $lcRespuesta = obtener_dao()->obtener_todos($lcParametro, null);
                break;
        }
        break;
    case 'POST':
        if ($lcMetodo === 'agregar') {
            $lcRespuesta = agregar(file_get_contents('php://input'));

            if (strpos($lcRespuesta, '<agregado>true</agregado>')) {
                $lnCodRespHttp = 201;    // 201 Created
            } elseif (strpos($lcRespuesta, '<agregado>false</agregado>')) {
                $lnCodRespHttp = 409;    // 409 Conflict
            } elseif (strpos($lcRespuesta, '<error>')) {
                $lnCodRespHttp = 400;    // 400 Bad Request
            }
        }
        break;
    case 'PUT':
        if ($lcMetodo === 'modificar') {
            # code...
        }
        break;
    case 'DELETE':
        if ($lcMetodo === 'borrar') {
            # code...
        }
        break;
    default:
        $lnCodRespHttp = 405;    // 405 Method Not Allowed
        break;
}

if ($lcRespuesta === '' and $lnCodRespHttp === 200) {
    $lnCodRespHttp = 404;    // 404 Not Found
}

header($_SERVER['SERVER_PROTOCOL'] .
    obtener_nombre_estado_respuesta_http($lnCodRespHttp), true, $lnCodRespHttp);
echo $lcRespuesta;

/** ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *
*                             FUNCTIONS SECTION                              *
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function validar($tcModulo, $tcMetodo) {
    if (!isset($tcModulo)
        or gettype($tcModulo) !== 'string'
        or empty($tcModulo)
        or $tcModulo !== 'marcas1'
    ) {
        return false;
    }

    if (!isset($tcMetodo)
        or gettype($tcMetodo) !== 'string'
        or empty($tcMetodo)
        or !($tcMetodo === 'existe-codigo'
        or $tcMetodo === 'existe-nombre'
        or $tcMetodo === 'esta-vigente'
        or $tcMetodo === 'esta-relacionado'
        or $tcMetodo === 'contar'
        or $tcMetodo === 'obtener-nuevo-codigo'
        or $tcMetodo === 'obtener-por-codigo'
        or $tcMetodo === 'obtener-por-nombre'
        or $tcMetodo === 'obtener-todos'
        or $tcMetodo === 'agregar'
        or $tcMetodo === 'modificar'
        or $tcMetodo === 'borrar')
    ) {
        return false;
    }

    return true;
}

#-------------------------------------------------------------------------------
function obtener_dao() {
    $loFabricaDao = fabrica_dao::obtener_fabrica_dao(BD_COM);
    return $loFabricaDao->obtener_dao_marcas1();
}

#-------------------------------------------------------------------------------
function agregar($tcXml) {
    $lcRespuesta = '';

    if (!$tcXml) {
        $lcRespuesta = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos><error><mensaje>No se recibió ningún XML.</mensaje>' .
            '</error></datos>';
    }

    if (!$lcRespuesta and !es_cadena_xml($tcXml)) {
        $lcRespuesta = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos><error><mensaje>Error al procesar el XML.</mensaje>' .
            '</error></datos>';
    }

    if (!$lcRespuesta) {
        try {
            $loXml = new SimpleXMLElement($tcXml);

            $codigo = (int) $loXml->registro->codigo;
            $nombre = (string) $loXml->registro->nombre;
            $vigente = (bool) $loXml->registro->vigente;

            $loDto = obtener_dao()->obtener_dto();

            if (is_object($loDto)) {
                $loDto->establecer_codigo($codigo);
                $loDto->establecer_nombre($nombre);
                $loDto->establecer_vigente($vigente);

                $lcRespuesta = obtener_dao()->agregar($loDto);
            }
        } catch (Exception $ex) {
            $lcRespuesta = '<?xml version="1.0" encoding="Windows-1252"?>' .
                '<datos><error><mensaje>Error al procesar el XML: ' .
                $ex->getMessage() . '</mensaje></error></datos>';
        }
    }

    return $lcRespuesta;
}
