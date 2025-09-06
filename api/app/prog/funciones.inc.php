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

function es_cadena_json($tcJson) {
    json_decode($tcJson);
    return json_last_error() === JSON_ERROR_NONE;
}

#-------------------------------------------------------------------------------
function es_cadena_xml($tcXml) {
    $llValido = false;

    libxml_use_internal_errors(true); // Suprime los errores.

    if (simplexml_load_string($tcXml)) {
        $llValido = true;
    }

    libxml_clear_errors(); // Limpia los errores.

    return $llValido;
}

#-------------------------------------------------------------------------------
function obtener_nombre_estado_respuesta_http($tnCodRespHttp) {
    if (gettype($tnCodRespHttp) !== 'integer') {
        return '';
    }

    $lcCodRespHttp = '';

    switch ($tnCodRespHttp) {
        case 200:
            $lcCodRespHttp = ' 200 OK';
            break;
        case 201:
            $lcCodRespHttp = ' 201 Created';
            break;
        case 202:
            $lcCodRespHttp = ' 202 Accepted';
            break;
        case 400:
            $lcCodRespHttp = ' 400 Bad Request';
            break;
        case 404:
            $lcCodRespHttp = ' 404 Not Found';
            break;
        case 405:
            $lcCodRespHttp = ' 405 Method Not Allowed';
            break;
        case 409:
            $lcCodRespHttp = ' 409 Conflict';
            break;
        case 418:
            $lcCodRespHttp = " 418 I'm a teapot";
            break;
    }

    return $lcCodRespHttp;
}
