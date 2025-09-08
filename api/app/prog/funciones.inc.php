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
 * Envía una respuesta HTTP con el código de estado y contenido especificados.
 *
 * Esta función establece el código de estado HTTP apropiado y envía el
 * contenido como respuesta. Si los parámetros no son válidos,
 * usa valores predeterminados.
 *
 * @param integer $tnCodigo Código de estado HTTP a enviar. Si no es integer,
 *                          se usa 404.
 * @param string $tcContenido Contenido a enviar en el cuerpo de la respuesta.
 *                            Si no es string, se convierte a string vacío.
 *
 * @return void
 *
 * @uses obtener_nombre_estado_respuesta_http() Para obtener el mensaje del
 *       código HTTP.
 * @uses header() Para establecer las cabeceras HTTP.
 *
 * @example
 * // Envía respuesta 200 con contenido JSON.
 * enviar_respuesta_http(200, '{"status": "success"}');
 *
 * @example
 * // Envía respuesta 404 vacía (por parámetro no integer).
 * enviar_respuesta_http('invalid', 'contenido');
 *
 * @example
 * // Envía respuesta 500 con mensaje de error.
 * enviar_respuesta_http(500, 'Error interno del servidor');
 */
function enviar_respuesta_http($tnCodigo, $tcContenido) {
    if (!is_integer($tnCodigo)) {
        $tnCodigo = 404;
    }

    if (!is_string($tcContenido)) {
        $tcContenido = '';
    }

    header($_SERVER['SERVER_PROTOCOL'] . ' ' .
        obtener_nombre_estado_respuesta_http($tnCodigo), true, $tnCodigo);
    echo $tcContenido;
}

/**
 * Verifica si una cadena es un JSON válido.
 *
 * Esta función comprueba que el parámetro proporcionado sea una cadena no vacía
 * y que tenga una sintaxis JSON válida.
 *
 * @param string $tcJson El valor a verificar.
 *
 * @return bool Retorna true si el parámetro es una cadena no vacía y contiene
 *              JSON válido.
 *              Retorna false en cualquier otro caso (no string, string vacío o
 *              JSON inválido).
 *
 * @example
 * es_cadena_json('{"nombre": "Juan"}');    // true
 * es_cadena_json('invalid json');          // false
 * es_cadena_json('');                      // false
 * es_cadena_json(null);                    // false
 */
function es_cadena_json($tcJson) {
    if (!is_string($tcJson) || trim($tcJson) === '') {
        return false;
    }

    json_decode($tcJson);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Verifica si una cadena es un XML válido.
 *
 * Esta función comprueba que el parámetro proporcionado sea una cadena no vacía
 * y que tenga una sintaxis XML válida utilizando la biblioteca libxml.
 *
 * @param string $tcXml El valor a verificar.
 *
 * @return bool Retorna true si el parámetro es una cadena no vacía y contiene
 *              XML válido.
 *              Retorna false en cualquier otro caso (no string, string vacío o
 *              XML inválido).
 *
 * @uses libxml_use_internal_errors() Para capturar errores de parsing sin
 *                                    mostrar warnings.
 * @uses simplexml_load_string() Para parsear la cadena XML.
 * @uses libxml_clear_errors() Para limpiar los errores después de la
 *                             verificación.
 *
 * @example
 * es_cadena_xml('<root><element>valor</element></root>');    // true
 * es_cadena_xml('<root>invalid xml');                        // false
 * es_cadena_xml('');                                         // false
 * es_cadena_xml(null);                                       // false
 */
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

/**
 * Genera una respuesta XML estructurada para errores.
 *
 * Esta función crea un documento XML con una estructura predefinida para
 * manejar mensajes de error en respuestas de servicios web o APIs.
 *
 * @param string $tcMensaje El mensaje de error a incluir en la respuesta XML.
 *                          Si no es un string, se convertirá a string vacío.
 *
 * @return string Documento XML con la estructura de error que incluye:
 *                - Encabezado XML (definido por la constante ENCABEZADO_XML)
 *                - Elemento raíz <datos>
 *                - Elemento <error> conteniendo el <mensaje>
 *
 * @uses ENCABEZADO_XML Constante que debe contener el encabezado XML apropiado
 *                      (ej: '<?xml version="1.0" encoding="UTF-8"?>')
 *
 * @example
 * // Retorna: <?xml version="1.0"?><datos><error><mensaje>Error de conexión
 * </mensaje></error></datos>
 * generar_error_xml('Error de conexión');
 *
 * @example
 * // Retorna: <?xml version="1.0"?><datos><error><mensaje></mensaje></error>
 * </datos>
 * generar_error_xml(null);
 */
function generar_error_xml($tcMensaje) {
    if (!is_string($tcMensaje)) {
        $tcMensaje = '';
    }

    return ENCABEZADO_XML . '<datos><error><mensaje>' . $tcMensaje .
        '</mensaje></error></datos>';
}

/**
 * Obtiene el mensaje estándar HTTP asociado a un código de estado.
 *
 * Esta función retorna la descripción oficial del código de estado HTTP
 * proporcionado.
 * Si el código no está en la lista predefinida, retorna 'Unknown Status'.
 *
 * @param integer $tnCodigo El código de estado HTTP a verificar.
 *
 * @return string El mensaje oficial del código HTTP seguido del código
 *                numérico, o 'Unknown Status' si el código no está reconocido.
 *
 * @example
 * obtener_nombre_estado_respuesta_http(200);      // '200 OK'
 * obtener_nombre_estado_respuesta_http(404);      // '404 Not Found'
 * obtener_nombre_estado_respuesta_http(999);      // 'Unknown Status'
 * obtener_nombre_estado_respuesta_http('200');    // 'Unknown Status'
 *                                                    (porque no es integer)
 */
function obtener_nombre_estado_respuesta_http($tnCodigo) {
    if (!is_integer($tnCodigo)) {
        return 'Unknown Status';
    }

    $laEstados = array(
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        400 => '400 Bad Request',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        409 => '409 Conflict',
        418 => "418 I'm a teapot",
        500 => '500 Internal Server Error'
    );

    return isset($laEstados[$tnCodigo]) ?
        $laEstados[$tnCodigo] : 'Unknown Status';
}

/**
 * Obtiene y convierte el valor de un nodo XML según el tipo especificado.
 *
 * Esta función toma un nodo XML, verifica si existe y no está vacío, y luego
 * convierte su valor al tipo de dato especificado. Si el nodo no existe o está
 * vacío, retorna un valor predeterminado.
 *
 * @param SimpleXMLElement|object $toNodo El nodo XML del cual extraer el valor.
 * @param string $tcTipo Tipo de conversión deseada. Valores aceptados:
 *               'string', 'int', 'bool'. Por defecto: 'string'.
 * @param mixed $tvPredeterminado Valor a retornar si el nodo no existe o está
 *              vacío. Por defecto: '' (string vacío).
 *
 * @return mixed El valor convertido al tipo especificado o el valor
 *               predeterminado.
 *
 * @example
 * // Para un nodo XML: <edad>25</edad>
 * obtener_valor_xml($nodo, 'int', 0);    // returna: 25
 *
 * @example
 * // Para un nodo XML: <activo>true</activo>
 * obtener_valor_xml($nodo, 'bool', false);    // returna: true
 *
 * @example
 * // Para un nodo que no existe.
 * obtener_valor_xml($nodoInexistente, 'string', 'default');
 * // returna: 'default'
 */
function obtener_valor_xml(
    $toNodo,
    $tcTipo = 'string',
    $tvPredeterminado = ''
) {
    if (!isset($toNodo) || trim((string) $toNodo) === '') {
        return $tvPredeterminado;
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
