<?php
/**
* funciones.inc.php
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

include_once 'app\prog\constantes.inc.php';

/**
 * Enví­a respuesta HTTP con código y contenido.
 *
 * @param int $tnCodigo Código HTTP.
 * @param string $tcContenido Contenido de la respuesta.
 * @return void
 */
function enviar_respuesta_http($tnCodigo, $tcContenido) {
    header($_SERVER['SERVER_PROTOCOL'] .
        obtener_nombre_estado_respuesta_http($tnCodigo), true, $tnCodigo);
    echo $tcContenido;
}

#-------------------------------------------------------------------------------
function es_cadena_json($tcJson) {
    if (!is_string($tcJson) || trim($tcJson) === '') {
        return false;
    }

    json_decode($tcJson);
    return json_last_error() === JSON_ERROR_NONE;
}

#-------------------------------------------------------------------------------
function es_cadena_xml($tcXml) {
    if (!is_string($tcXml) || trim($tcXml) === '') {
        return false;
    }

    libxml_use_internal_errors(true);

    $loXml = simplexml_load_string($tcXml);
    $llValido = ($loXml !== false);

    libxml_clear_errors();
    libxml_use_internal_errors(false);

    return $llValido;
}

#-------------------------------------------------------------------------------
function generar_error_xml($tcMensaje) {
    return ENCABEZADO_XML . '<datos><error><mensaje>' . $tcMensaje .
        '</mensaje></error></datos>';
}

#-------------------------------------------------------------------------------
function obtener_nombre_estado_respuesta_http($tnCodRespHttp) {
    if (!is_int($tnCodRespHttp)) {
        return '';
    }

    $laEstados = array(
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        400 => '400 Bad Request',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        409 => '409 Conflict',
        418 => "418 I'm a teapot"
    );

    return isset($laEstados[$tnCodRespHttp]) ?
        $laEstados[$tnCodRespHttp] : ' Unknown Status';
}

/**
 * Obtiene valor de un nodo XML con tipo y valor predeterminado.
 *
 * @param mixed $toNodo Nodo XML.
 * @param string $tcTipo Tipo de dato (string, int, bool).
 * @param mixed $tcPredeterminado Valor predeterminado.
 * @return mixed Valor convertido.
 */
function obtener_valor_xml($toNodo, $tcTipo = 'string', $tcPredeterminado = '')
{
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
