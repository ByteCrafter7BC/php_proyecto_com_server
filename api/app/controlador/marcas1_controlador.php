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

header('Content-Type: application/xml; charset=Windows-1252');

if (!isset($lcModulo) or !isset($lcMetodo) or !validar($lcModulo, $lcMetodo)) {
    header($_SERVER['SERVER_PROTOCOL'] . " 418 I'm a teapot", true, 418);
    die();
}

$lcMetodoSolicitud = $_SERVER['REQUEST_METHOD'];
$lcRespuesta = '';

switch ($lcMetodoSolicitud) {
    case 'GET':
        switch ($lcMetodo) {
            case 'buscar':
                $lcRespuesta = buscar($lcParametro, null);
                break;
            case 'buscar-por-codigo':
                if (isset($lcParametro)) {
                    $lcRespuesta = buscar_por_codigo((int) $lcParametro);
                }
                break;
            case 'buscar-por-nombre':
                if (isset($lcParametro)) {
                    $lcRespuesta = buscar_por_nombre($lcParametro);
                }
                break;
        }
        break;
    case 'POST':
        if ($lcMetodo === 'agregar') {
            # code...
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
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed', true,
            405);
        break;
}

if ($lcRespuesta !== '') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
}

echo $lcRespuesta;

/** ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *
*                             FUNCTIONS SECTION                              *
* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

/**
* Realiza las valicaciones iniciales.
*/
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
        or !($tcMetodo === 'agregar'
        or $tcMetodo === 'borrar'
        or $tcMetodo === 'buscar'
        or $tcMetodo === 'buscar-por-codigo'
        or $tcMetodo === 'buscar-por-nombre'
        or $tcMetodo === 'modificar')
    ) {
        return false;
    }

    return true;
}

/**
* Devuelve el DAO (Data Access Object).
*/
function obtener_dao() {
    $loFabricaDao = fabrica_dao::obtener_fabrica_dao(BD_COM);
    return $loFabricaDao->obtener_dao_marcas1();
}

/**
* Realiza una búsqueda por código.
*
* @param integer $tnCodigo
* Especifica el código a buscar.
*
* @return string
*/
function buscar_por_codigo($tnCodigo) {
    $loDao = obtener_dao();
    return $loDao->obtener_por_codigo($tnCodigo);
}

/**
* Realiza una búsqueda por nombre.
*
* @param string $tcNombre
* Especifica el nombre a buscar.
*
* @return string
*/
function buscar_por_nombre($tcNombre) {
    $loDao = obtener_dao();
    return $loDao->obtener_por_nombre($tcNombre);
}

/**
* Recupera todos los registros.
*
* @return string
*/
function buscar($tcCondicionFiltro, $tcOrden) {
    $loDao = obtener_dao();
    return $loDao->obtener_todos($tcCondicionFiltro, $tcOrden);
}

/**
* Inserts a new record.
*
* @param string $tcModel
* JSON string representing the model data to be inserted.
*
* @return string
*/
function insert($tcModel) {
    $loDao = get_dao();
    return $loDao->insert($tcModel);
}

/**
* Deletes an existing record.
*
* @param integer $tnId
* Specifies the id to be deleted.
*
* @return string
*/
function delete($tnId) {
    $loDao = get_dao();
    return $loDao->delete('{ "id": ' . $tnId . ' }');
}
