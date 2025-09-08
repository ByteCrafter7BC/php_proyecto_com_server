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

if (empty($lcModulo) || empty($lcMetodo) || !validar($lcModulo, $lcMetodo)) {
    enviar_respuesta_http(418, '');
    exit;
}

$lcMetodoHttp = $_SERVER['REQUEST_METHOD'];

if (!metodo_permitido($lcMetodoHttp, $lcMetodo)) {
    enviar_respuesta_http(405, '');    // 405 Method Not Allowed
    exit;
}

$lcRespuesta = '';
$lnCodRespHttp = 200;    // 200 OK
$loDao = obtener_dao();

switch ($lcMetodoHttp) {
    case 'GET':
        $lcRespuesta = procesar_get($lcMetodo, $lcParametro, $loDao);
        break;
    case 'POST':
        if ($lcMetodo === 'agregar') {
            $laResultado = agregar(file_get_contents('php://input'), $loDao);
            $lcRespuesta = $laResultado['contenido'];
            $lnCodRespHttp = $laResultado['codigo'];
        }
        break;
    case 'PUT':
        if ($lcMetodo === 'modificar') {
            $laResultado = modificar(file_get_contents('php://input'), $loDao);
            $lcRespuesta = $laResultado['contenido'];
            $lnCodRespHttp = $laResultado['codigo'];
        }
        break;
    case 'DELETE':
        if ($lcMetodo === 'borrar') {
            $laResultado = borrar(file_get_contents('php://input'), $loDao);
            $lcRespuesta = $laResultado['contenido'];
            $lnCodRespHttp = $laResultado['codigo'];
        }
        break;
    default:
        $lnCodRespHttp = 405;    // 405 Method Not Allowed
        break;
}

if ($lcRespuesta === '' && $lnCodRespHttp === 200) {
    $lnCodRespHttp = 404;    // 404 Not Found
}

enviar_respuesta_http($lnCodRespHttp, $lcRespuesta);

/** ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *
*                             FUNCTIONS SECTION                              *
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

#-------------------------------------------------------------------------------
function validar($tcModulo, $tcMetodo) {
    // Validar módulo.
    if (!is_string($tcModulo) || $tcModulo !== 'marcas1') {
        return false;
    }

    // Lista de métodos válidos.
    $laMetodosValidos = array(
        'existe-codigo', 'existe-nombre', 'esta-vigente', 'esta-relacionado',
        'contar', 'obtener-nuevo-codigo', 'obtener-por-codigo',
        'obtener-por-nombre', 'obtener-todos', 'agregar', 'modificar', 'borrar'
    );

    // Validar método.
    return is_string($tcMetodo) && in_array($tcMetodo, $laMetodosValidos, true);
}

#-------------------------------------------------------------------------------
function obtener_dao() {
    $loFabricaDao = fabrica_dao::obtener_fabrica_dao(BD_COM);
    return $loFabricaDao->obtener_dao_marcas1();
}

#-------------------------------------------------------------------------------
function agregar($tcXml, $toDao) {
    if (empty($tcXml) || !es_cadena_xml($tcXml)) {
        return generar_error_xml('XML inválido o no recibido.');
    }

    try {
        $loXml = new SimpleXMLElement($tcXml);

        if (!isset($loXml->registro)) {
            return generar_error_xml('El nodo <registro> no está presente ' .
                'en el XML.');
        }

        $loRegistro = $loXml->registro;
        $loDto = $toDao->obtener_dto();

        if (!($loDto instanceof stdClass)) {
            return generar_error_xml('Error al obtener el objeto DTO.');
        }

        // Validaciones defensivas para evitar errores por nodos vacíos.
        $codigo = obtener_valor_xml($loRegistro->codigo, 'int', 0);
        $nombre = obtener_valor_xml($loRegistro->nombre);
        $vigente = obtener_valor_xml($loRegistro->vigente, 'bool', false);

        $loDto->establecer_codigo($codigo);
        $loDto->establecer_nombre($nombre);
        $loDto->establecer_vigente($vigente);

        $lcXml = $toDao->agregar($loDto);

        if (strpos($lcXml, '<agregado>true</agregado>')) {
            return array('codigo' => 201, 'contenido' => $lcXml);
        } elseif (strpos($lcXml, '<agregado>false</agregado>')) {
            return array('codigo' => 409, 'contenido' => $lcXml);
        } else {
            return array('codigo' => 400, 'contenido' => $lcXml);
        }
    } catch (Exception $ex) {
        return generar_error_xml('Error al procesar el XML: ' .
            htmlspecialchars($ex->getMessage(), ENT_XML1, 'Windows-1252'));
    }
}

#-------------------------------------------------------------------------------
function modificar($tcXml, $toDao) {
    if (empty($tcXml) || !es_cadena_xml($tcXml)) {
        return generar_error_xml('XML inválido o no recibido.');
    }

    try {
        $loXml = new SimpleXMLElement($tcXml);

        if (!isset($loXml->registro)) {
            return generar_error_xml('El nodo <registro> no está presente ' .
                'en el XML.');
        }

        $loRegistro = $loXml->registro;
        $loDto = $toDao->obtener_dto();

        if (!($loDto instanceof stdClass)) {
            return generar_error_xml('Error al obtener el objeto DTO.');
        }

        // Validaciones defensivas para evitar errores por nodos vacíos.
        $codigo = obtener_valor_xml($loRegistro->codigo, 'int', 0);
        $nombre = obtener_valor_xml($loRegistro->nombre);
        $vigente = obtener_valor_xml($loRegistro->vigente, 'bool', false);

        $loDto->establecer_codigo($codigo);
        $loDto->establecer_nombre($nombre);
        $loDto->establecer_vigente($vigente);

        $lcXml = $toDao->modificar($loDto);

        if (strpos($lcXml, '<modificado>true</modificado>')) {
            return array('codigo' => 201, 'contenido' => $lcXml);
        } elseif (strpos($lcXml, '<modificado>false</modificado>')) {
            return array('codigo' => 409, 'contenido' => $lcXml);
        } else {
            return array('codigo' => 400, 'contenido' => $lcXml);
        }
    } catch (Exception $ex) {
        return generar_error_xml('Error al procesar el XML: ' .
            htmlspecialchars($ex->getMessage(), ENT_XML1, 'Windows-1252'));
    }
}

#-------------------------------------------------------------------------------
function borrar($tcXml, $toDao) {
    if (empty($tcXml) || !es_cadena_xml($tcXml)) {
        return generar_error_xml('XML inválido o no recibido.');
    }

    try {
        $loXml = new SimpleXMLElement($tcXml);

        if (!isset($loXml->registro)) {
            return generar_error_xml('El nodo <registro> no está presente ' .
                'en el XML.');
        }

        $lcXml = $toDao->borrar(
            obtener_valor_xml($loXml->registro->codigo, 'int', 0));

        if (strpos($lcXml, '<borrado>true</borrado>')) {
            return array('codigo' => 201, 'contenido' => $lcXml);
        } elseif (strpos($lcXml, '<borrado>false</borrado>')) {
            return array('codigo' => 409, 'contenido' => $lcXml);
        } else {
            return array('codigo' => 400, 'contenido' => $lcXml);
        }
    } catch (Exception $ex) {
        return generar_error_xml('Error al procesar el XML: ' .
            htmlspecialchars($ex->getMessage(), ENT_XML1, 'Windows-1252'));
    }
}

#-------------------------------------------------------------------------------
function procesar_get($tcMetodo, $tcParametro, $toDao) {
    switch ($tcMetodo) {
        case 'existe-codigo':
            return $toDao->existe_codigo((int) $tcParametro);
        case 'existe-nombre':
            return $toDao->existe_nombre($tcParametro);
        case 'esta-vigente':
            return $toDao->esta_vigente((int) $tcParametro);
        case 'esta-relacionado':
            return $toDao->esta_relacionado((int) $tcParametro);
        case 'contar':
            return $toDao->contar($tcParametro);
        case 'obtener-nuevo-codigo':
            return $toDao->obtener_nuevo_codigo();
        case 'obtener-por-codigo':
            return $toDao->obtener_por_codigo((int) $tcParametro);
        case 'obtener-por-nombre':
            return $toDao->obtener_por_nombre($tcParametro);
        case 'obtener-todos':
            return $toDao->obtener_todos($tcParametro, null);
    }

    return '';
}

#-------------------------------------------------------------------------------
function obtener_valor_xml(
    $toNodo,
    $tcTipo = 'string',
    $tcPredeterminado = ''
) {
    if (!isset($toNodo) || trim((string) $toNodo) === '') {
        return $tcPredeterminado;
    }

    switch ($tcTipo) {
        case 'int':
            return (int) $toNodo;
        case 'bool':
            return strtolower((string) $toNodo) === 'true';
        default:
            return (string) $toNodo;
    }
}

#-------------------------------------------------------------------------------
function enviar_respuesta_http($tnCodigo, $tcContenido) {
    header($_SERVER['SERVER_PROTOCOL'] .
        obtener_nombre_estado_respuesta_http($tnCodigo), true, $tnCodigo);
    echo $tcContenido;
}

#-------------------------------------------------------------------------------
function metodo_permitido($tcVerbo, $tcMetodo) {
    $laMetodosPorVerbo = array(
        'GET' => array('existe-codigo', 'existe-nombre', 'esta-vigente',
            'esta-relacionado', 'contar', 'obtener-nuevo-codigo',
            'obtener-por-codigo', 'obtener-por-nombre', 'obtener-todos'),
        'POST' => array('agregar'),
        'PUT' => array('modificar'),
        'DELETE' => array('borrar')
    );

    return isset($laMetodosPorVerbo[$tcVerbo])
        && in_array($tcMetodo, $laMetodosPorVerbo[$tcVerbo], true);
}
