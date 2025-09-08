<?php
/**
 * controlador_base.inc.php
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

/**
 * Controlador base.
 * Maneja operaciones CRUD a través de solicitudes HTTP con respuestas XML.
 *
 * @version 1.0
 */
class controlador_base {
    /**
     * @var string Módulo que maneja este controlador.
     */
    protected $cModulo;

    /**
     * @var string Método a ejecutar.
     */
    protected $tcMetodo;

    /**
     * @var string Parámetro de la solicitud.
     */
    protected $tcParametro;

    /**
     * @var string Método HTTP de la solicitud actual.
     */
    protected $tcMetodoHttp;

    /**
     * @var object Instancia del DAO para operaciones de datos.
     */
    protected $oDao;

    /**
     * Constructor de la clase.
     *
     * @param string $tcModulo Módulo que maneja este controlador.
     * @param string $tcMetodo Método a ejecutar.
     * @param string $tcParametro Parámetro de la solicitud.
     */
    public function __construct($tcModulo, $tcMetodo, $tcParametro = '') {
        $this->cModulo = $tcModulo;
        $this->cMetodo = $tcMetodo;
        $this->cParametro = $tcParametro;
        $this->cMetodoHttp = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Ejecuta el controlador y procesa la solicitud.
     *
     * @return void
     */
    public function ejecutar() {
        header('Content-Type: application/xml; charset=Windows-1252');

        if (!$this->tcMetodo_Valid() || !$this->tcModulo_Valid()) {
            enviar_respuesta_http(418, '');
            exit;
        }

        if (!$this->metodo_permitido()) {
            enviar_respuesta_http(405, '');
            exit;
        }

        if (!$this->obtener_dao()) {
            enviar_respuesta_http(500, '');
            exit;
        }

        $laResultado = $this->procesar_solicitud();

        if (
            $laResultado['contenido'] === ''
            && $laResultado['codigo'] === 200
        ) {
            $laResultado['codigo'] = 404;
        }

        enviar_respuesta_http($laResultado['codigo'],
            $laResultado['contenido']);
    }

    /** ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *
     *                            PROTECTED METHODS                           *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Valida módulo.
     *
     * @return bool True si es válido.
     */
    protected function tcModulo_Valid() {
        return is_string($this->cModulo) && trim($this->cModulo) !== '';
    }

    /**
     * Valida método.
     *
     * @return bool True si es válido.
     */
    protected function tcMetodo_Valid() {
        // Lista de métodos válidos.
        $laMetodosValidos = array(
            'existe-codigo', 'existe-nombre', 'esta-vigente',
            'esta-relacionado', 'contar', 'obtener-nuevo-codigo',
            'obtener-por-codigo', 'obtener-por-nombre',
            'obtener-todos', 'agregar', 'modificar', 'borrar'
        );
        return is_string($this->cMetodo) && trim($this->cMetodo) !== ''
            && in_array($this->cMetodo, $laMetodosValidos, true);
    }

    /**
     * Verifica si el método HTTP está permitido para el método solicitado.
     *
     * @return bool True si está permitido.
     */
    protected function metodo_permitido() {
        $laMetodosPorVerbo = array(
            'GET' => array('existe-codigo', 'existe-nombre', 'esta-vigente',
                'esta-relacionado', 'contar', 'obtener-nuevo-codigo',
                'obtener-por-codigo', 'obtener-por-nombre', 'obtener-todos'),
            'POST' => array('agregar'),
            'PUT' => array('modificar'),
            'DELETE' => array('borrar')
        );

        return isset($laMetodosPorVerbo[$this->cMetodoHttp])
            && in_array($this->cMetodo, $laMetodosPorVerbo[$this->cMetodoHttp],
            true);
    }

    /**
     * Obtiene la instancia del DAO correspondiente al módulo actual.
     *
     * @return bool True si se obtuvo una instancia válida del DAO.
     */
    protected function obtener_dao() {
        if (!is_string($this->cModulo) || $this->cModulo === '') {
            // Módulo no definido o inválido.
            return false;
        }

        $loFabricaDao = fabrica_dao::obtener_fabrica_dao(BD_COM);

        if (!is_object($loFabricaDao)) {
            // Fábrica no válida.
            return false;
        }

        $lcMetodo = 'obtener_dao_' . $this->cModulo;

        if (!method_exists($loFabricaDao, $lcMetodo)) {
            // Método no disponible en la fábrica.
            return false;
        }

        $loDao = $loFabricaDao->$lcMetodo();

        if (!is_object($loDao)) {
            // Instancia DAO inválida.
            return false;
        }

        $this->oDao = $loDao;

        return true;
    }

    /**
     * Procesa la solicitud según el método HTTP.
     *
     * @return array Resultado con código y contenido.
     */
    protected function procesar_solicitud() {
        switch ($this->cMetodoHttp) {
            case 'GET':
                return array('codigo' => 200,
                    'contenido' => $this->procesar_get());
            case 'POST':
                if ($this->cMetodo === 'agregar') {
                    return $this->guardar(file_get_contents('php://input'), 1);
                }
                break;
            case 'PUT':
                if ($this->cMetodo === 'modificar') {
                    return $this->guardar(file_get_contents('php://input'), 2);
                }
                break;
            case 'DELETE':
                if ($this->cMetodo === 'borrar') {
                    return $this->borrar(file_get_contents('php://input'));
                }
                break;
        }

        return array('codigo' => 405, 'contenido' => '');
    }

    /**
     * Procesa solicitudes GET.
     *
     * @return string Respuesta XML.
     */
    protected function procesar_get() {
        switch ($this->cMetodo) {
            case 'existe-codigo':
                return $this->oDao->existe_codigo((int) $this->cParametro);
            case 'existe-nombre':
                return $this->oDao->existe_nombre($this->cParametro);
            case 'esta-vigente':
                return $this->oDao->esta_vigente((int) $this->cParametro);
            case 'esta-relacionado':
                return $this->oDao->esta_relacionado((int) $this->cParametro);
            case 'contar':
                return $this->oDao->contar($this->cParametro);
            case 'obtener-nuevo-codigo':
                return $this->oDao->obtener_nuevo_codigo();
            case 'obtener-por-codigo':
                return $this->oDao->obtener_por_codigo((int) $this->cParametro);
            case 'obtener-por-nombre':
                return $this->oDao->obtener_por_nombre($this->cParametro);
            case 'obtener-todos':
                return $this->oDao->obtener_todos($this->cParametro, null);
        }

        return '';
    }

    /**
     * Procesa la operación guardar.
     *
     * @param string $tcXml Datos XML recibidos.
     * @param int $tnBandera Operación a realizar (1 = agregar ó 2 = modificar).
     * @return array Resultado con código y contenido.
     */
    protected function guardar($tcXml, $tnBandera) {
        if (!es_cadena_xml($tcXml)) {
            return array('codigo' => 400, 'contenido' =>
                generar_error_xml('XML inválido o no recibido.'));
        }

        if (
            !is_integer($tnBandera)
            || !($tnBandera >= 1 && $tnBandera <= 2)
        ) {
            return array('codigo' => 400, 'contenido' =>
                generar_error_xml('Tipo de operación inválido o no recibido.'));
        }

        if ($tnBandera == 1) {
            $lcMetodo = 'agregar';
            $lcExito = '<agregado>true</agregado>';
            $lcFallo = '<agregado>false</agregado>';
        } else {
            $lcMetodo = 'modificar';
            $lcExito = '<modificado>true</modificado>';
            $lcFallo = '<modificado>false</modificado>';
        }

        try {
            $loXml = new SimpleXMLElement($tcXml);

            if (!isset($loXml->registro)) {
                return array('codigo' => 400, 'contenido' => generar_error_xml(
                    'El nodo <registro> no está presente en el XML.'));
            }

            $loRegistro = $loXml->registro;
            $loDto = $this->oDao->obtener_dto();

            if (!is_object($loDto)) {
                // La clase devuelta por get_class($loDto) es 'variant'.
                // por ser un objeto COM.
                return array('codigo' => 400, 'contenido' =>
                    generar_error_xml('Error al obtener el objeto DTO.'));
            }

            // Validaciones defensivas para evitar errores por nodos vacíos.
            $codigo = obtener_valor_xml($loRegistro->codigo, 'int', 0);
            $nombre = obtener_valor_xml($loRegistro->nombre);
            $vigente = obtener_valor_xml($loRegistro->vigente, 'bool', false);

            $loDto->establecer_codigo($codigo);
            $loDto->establecer_nombre($nombre);
            $loDto->establecer_vigente($vigente);

            $lcXml = $this->oDao->$lcMetodo($loDto);

            if (strpos($lcXml, $lcExito)) {
                return array('codigo' => 201, 'contenido' => $lcXml);
            } elseif (strpos($lcXml, $lcFallo)) {
                return array('codigo' => 409, 'contenido' => $lcXml);
            } else {
                return array('codigo' => 400, 'contenido' => $lcXml);
            }
        } catch (Exception $ex) {
            return array('codigo' => 400, 'contenido' =>
                generar_error_xml('Error al procesar el XML: ' .
                htmlspecialchars($ex->getMessage(), ENT_XML1, 'Windows-1252')));
        }
    }

    /**
     * Procesa la operación borrar.
     *
     * @param string $tcXml Datos XML recibidos.
     * @return array Resultado con código y contenido.
     */
    protected function borrar($tcXml) {
        if (!es_cadena_xml($tcXml)) {
            return array('codigo' => 400, 'contenido' =>
                generar_error_xml('XML inválido o no recibido.'));
        }

        try {
            $loXml = new SimpleXMLElement($tcXml);

            if (!isset($loXml->registro)) {
                return array('codigo' => 400, 'contenido' => generar_error_xml(
                    'El nodo <registro> no está presente en el XML.'));
            }

            $lcXml = $this->oDao->borrar(
                obtener_valor_xml($loXml->registro->codigo, 'int', 0));

            if (strpos($lcXml, '<borrado>true</borrado>')) {
                return array('codigo' => 201, 'contenido' => $lcXml);
            } elseif (strpos($lcXml, '<borrado>false</borrado>')) {
                return array('codigo' => 409, 'contenido' => $lcXml);
            } else {
                return array('codigo' => 400, 'contenido' => $lcXml);
            }
        } catch (Exception $ex) {
            return array('codigo' => 400, 'contenido' =>
                generar_error_xml('Error al procesar el XML: ' .
                htmlspecialchars($ex->getMessage(), ENT_XML1, 'Windows-1252')));
        }
    }
}
