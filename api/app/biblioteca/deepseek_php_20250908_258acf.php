<?php
/**
 * dao_com.inc.php
 *
 * Clase abstracta que implementa la interfaz DAO para comunicación COM
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
 *
 * @package    Database
 * @subpackage DAO
 * @author     ByteCrafter7BC <bytecrafter7bc@gmail.com>
 * @copyright  2000-2025 ByteCrafter7BC
 * @license    https://www.gnu.org/licenses/ GNU GPL v3 o posterior
 * @version    1.0.0
 */

include_once 'app\biblioteca\dao.inc.php';

/**
 * Clase abstracta que implementa la interfaz DAO para componentes COM
 *
 * Proporciona métodos para realizar operaciones CRUD a través de componentes COM
 * y retorna los resultados en formato XML.
 *
 * @abstract
 */
abstract class dao_com implements dao
{
    /**
     * Nombre del componente COM
     *
     * @var string
     */
    protected $cCom;

    /**
     * Instancia del componente COM
     *
     * @var COM|null
     */
    protected $oConexion;

    /**
     * Verifica si existe un registro con el código especificado
     *
     * @param int $tnCodigo Código a verificar
     *
     * @return string XML con el resultado de la verificación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function existe_codigo($tnCodigo)
    {
        $llExiste = false;
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llExiste = $this->oConexion->existe_codigo($tnCodigo);
            }

            $lcXml = $this->generarXmlExisteCodigo($tnCodigo, $llExiste);
        } catch (Exception $ex) {
            error_log('ERROR en existe_codigo: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Verifica si existe un registro con el nombre especificado
     *
     * @param string $tcNombre Nombre a verificar
     *
     * @return string XML con el resultado de la verificación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function existe_nombre($tcNombre)
    {
        $llExiste = false;
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llExiste = $this->oConexion->existe_nombre($tcNombre);
            }

            $lcXml = $this->generarXmlExisteNombre($tcNombre, $llExiste);
        } catch (Exception $ex) {
            error_log('ERROR en existe_nombre: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Verifica si un registro está vigente
     *
     * @param int $tnCodigo Código del registro a verificar
     *
     * @return string XML con el resultado de la verificación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function esta_vigente($tnCodigo)
    {
        $llVigente = false;
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llVigente = $this->oConexion->esta_vigente($tnCodigo);
            }

            $lcXml = $this->generarXmlEstaVigente($tnCodigo, $llVigente);
        } catch (Exception $ex) {
            error_log('ERROR en esta_vigente: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Verifica si un registro está relacionado con otros
     *
     * @param int $tnCodigo Código del registro a verificar
     *
     * @return string XML con el resultado de la verificación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function esta_relacionado($tnCodigo)
    {
        $llRelacionado = false;
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llRelacionado = $this->oConexion->esta_relacionado($tnCodigo);
            }

            $lcXml = $this->generarXmlEstaRelacionado($tnCodigo, $llRelacionado);
        } catch (Exception $ex) {
            error_log('ERROR en esta_relacionado: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Cuenta el número de registros que cumplen con una condición
     *
     * @param string $tcCondicionFiltro Condición de filtro
     *
     * @return string XML con el resultado del conteo
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function contar($tcCondicionFiltro)
    {
        $lnNumReg = 0;
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lnNumReg = $this->oConexion->contar($tcCondicionFiltro);
            }

            $lcXml = $this->generarXmlContar($lnNumReg);
        } catch (Exception $ex) {
            error_log('ERROR en contar: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Obtiene un nuevo código para un registro
     *
     * @return string XML con el nuevo código
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function obtener_nuevo_codigo()
    {
        $lnNuevoCodigo = 0;
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lnNuevoCodigo = $this->oConexion->obtener_nuevo_codigo();
            }

            $lcXml = $this->generarXmlNuevoCodigo($lnNuevoCodigo);
        } catch (Exception $ex) {
            error_log('ERROR en obtener_nuevo_codigo: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Obtiene un registro por su código
     *
     * @param int $tnCodigo Código del registro
     *
     * @return string XML con los datos del registro
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function obtener_por_codigo($tnCodigo)
    {
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $loDto = $this->oConexion->obtener_por_codigo($tnCodigo);
                $lcXml = $this->convertir_dto_a_xml($loDto);
            }
        } catch (Exception $ex) {
            error_log('ERROR en obtener_por_codigo: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Obtiene un registro por su nombre
     *
     * @param string $tcNombre Nombre del registro
     *
     * @return string XML con los datos del registro
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function obtener_por_nombre($tcNombre)
    {
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $loDto = $this->oConexion->obtener_por_nombre($tcNombre);
                $lcXml = $this->convertir_dto_a_xml($loDto);
            }
        } catch (Exception $ex) {
            error_log('ERROR en obtener_por_nombre: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Obtiene todos los registros que cumplen con una condición
     *
     * @param string $tcCondicionFiltro Condición de filtro
     * @param string $tcOrden           Ordenamiento de los resultados
     *
     * @return string XML con los datos de los registros
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function obtener_todos($tcCondicionFiltro, $tcOrden)
    {
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lcXml = $this->oConexion->obtener_todos($tcCondicionFiltro, $tcOrden);
            }
        } catch (Exception $ex) {
            error_log('ERROR en obtener_todos: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Obtiene el objeto DTO
     *
     * @return mixed Objeto DTO o null si hay error
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function obtener_dto()
    {
        $loDto = null;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $loDto = $this->oConexion->obtener_dto();
            }
        } catch (Exception $ex) {
            error_log('ERROR en obtener_dto: ' . $ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $loDto;
    }

    /**
     * Agrega un nuevo registro
     *
     * @param mixed $toDto Objeto DTO con los datos del registro
     *
     * @return string XML con el resultado de la operación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function agregar($toDto)
    {
        $llAgregado = false;
        $lcMensaje = '';
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llAgregado = $this->oConexion->agregar($toDto);

                if (!$llAgregado) {
                    $lcMensaje = $this->oConexion->obtener_ultimo_error();
                }
            }

            $lcXml = $this->generarXmlOperacion(
                $toDto->obtener_codigo(),
                $llAgregado,
                $lcMensaje,
                'agregado'
            );
        } catch (Exception $ex) {
            error_log('ERROR en agregar: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Modifica un registro existente
     *
     * @param mixed $toDto Objeto DTO con los datos actualizados
     *
     * @return string XML con el resultado de la operación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function modificar($toDto)
    {
        $llModificado = false;
        $lcMensaje = '';
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llModificado = $this->oConexion->modificar($toDto);

                if (!$llModificado) {
                    $lcMensaje = $this->oConexion->obtener_ultimo_error();
                }
            }

            $lcXml = $this->generarXmlOperacion(
                $toDto->obtener_codigo(),
                $llModificado,
                $lcMensaje,
                'modificado'
            );
        } catch (Exception $ex) {
            error_log('ERROR en modificar: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /**
     * Borra un registro
     *
     * @param int $tnCodigo Código del registro a borrar
     *
     * @return string XML con el resultado de la operación
     *
     * @throws Exception Si ocurre un error en la conexión COM
     */
    public function borrar($tnCodigo)
    {
        $llBorrado = false;
        $lcMensaje = '';
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llBorrado = $this->oConexion->borrar($tnCodigo);

                if (!$llBorrado) {
                    $lcMensaje = $this->oConexion->obtener_ultimo_error();
                }
            }

            $lcXml = $this->generarXmlOperacion(
                $tnCodigo,
                $llBorrado,
                $lcMensaje,
                'borrado'
            );
        } catch (Exception $ex) {
            error_log('ERROR en borrar: ' . $ex->getMessage());
            $lcXml = $this->generarXmlError($ex->getMessage());
        } finally {
            $this->desconectar();
        }

        return $lcXml;
    }

    /** ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *
     *                            PROTECTED METHODS                            *
     * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Establece conexión con el componente COM
     *
     * @return void
     *
     * @throws Exception Si no se puede crear la instancia COM
     */
    protected function conectar()
    {
        if (isset($this->cCom) && !empty($this->cCom)) {
            try {
                // $this->oConexion = new COM($this->cCom, NULL, CP_UTF8);
                $this->oConexion = new COM($this->cCom, null, CP_ACP);
            } catch (Exception $ex) {
                error_log('ERROR al conectar COM: ' . $ex->getMessage());
                throw new Exception('No se pudo conectar al componente COM: ' . $this->cCom);
            }
        }
    }

    /**
     * Cierra la conexión con el componente COM
     *
     * @return void
     */
    protected function desconectar()
    {
        if (isset($this->oConexion)) {
            $this->oConexion = null;
        }
    }

    /**
     * Convierte un objeto DTO a formato XML
     *
     * @param mixed $toDto Objeto DTO a convertir
     *
     * @return string XML con los datos del DTO o cadena vacía si no es objeto
     */
    protected function convertir_dto_a_xml($toDto)
    {
        if (!is_object($toDto)) {
            return '';
        }

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<nombre>{{nombre}}</nombre>' .
                    '<vigente>{{vigente}}</vigente>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $toDto->obtener_codigo(), $lcXml);
        $lcXml = str_replace('{{nombre}}', htmlspecialchars($toDto->obtener_nombre(), ENT_XML1, 'Windows-1252'), $lcXml);
        $lcXml = str_replace('{{vigente}}', $toDto->esta_vigente() ? 'true' : 'false', $lcXml);

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de existencia de código
     *
     * @param int  $tnCodigo Código verificado
     * @param bool $llExiste Resultado de la verificación
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlExisteCodigo($tnCodigo, $llExiste)
    {
        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>' . $tnCodigo . '</codigo>' .
                    '<existe>' . ($llExiste ? 'true' : 'false') . '</existe>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de existencia de nombre
     *
     * @param string $tcNombre Nombre verificado
     * @param bool   $llExiste Resultado de la verificación
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlExisteNombre($tcNombre, $llExiste)
    {
        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<nombre>' . htmlspecialchars($tcNombre, ENT_XML1, 'Windows-1252') . '</nombre>' .
                    '<existe>' . ($llExiste ? 'true' : 'false') . '</existe>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de verificación de vigencia
     *
     * @param int  $tnCodigo  Código verificado
     * @param bool $llVigente Resultado de la verificación
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlEstaVigente($tnCodigo, $llVigente)
    {
        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>' . $tnCodigo . '</codigo>' .
                    '<vigente>' . ($llVigente ? 'true' : 'false') . '</vigente>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de verificación de relación
     *
     * @param int  $tnCodigo      Código verificado
     * @param bool $llRelacionado Resultado de la verificación
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlEstaRelacionado($tnCodigo, $llRelacionado)
    {
        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>' . $tnCodigo . '</codigo>' .
                    '<relacionado>' . ($llRelacionado ? 'true' : 'false') . '</relacionado>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de conteo
     *
     * @param int $lnNumReg Número de registros
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlContar($lnNumReg)
    {
        $modulo = substr($this->cCom, strpos($this->cCom, '.') + 5);

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<modulo>' . htmlspecialchars($modulo, ENT_XML1, 'Windows-1252') . '</modulo>' .
                    '<numero_registros>' . $lnNumReg . '</numero_registros>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de nuevo código
     *
     * @param int $lnNuevoCodigo Nuevo código generado
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlNuevoCodigo($lnNuevoCodigo)
    {
        $modulo = substr($this->cCom, strpos($this->cCom, '.') + 5);

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<modulo>' . htmlspecialchars($modulo, ENT_XML1, 'Windows-1252') . '</modulo>' .
                    '<nuevo_codigo>' . $lnNuevoCodigo . '</nuevo_codigo>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de operaciones (agregar, modificar, borrar)
     *
     * @param int    $tnCodigo   Código del registro
     * @param bool   $llExito    Resultado de la operación
     * @param string $tcMensaje  Mensaje de error (si aplica)
     * @param string $tcTipo     Tipo de operación
     *
     * @return string XML con la respuesta
     */
    protected function generarXmlOperacion($tnCodigo, $llExito, $tcMensaje, $tcTipo)
    {
        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>' . $tnCodigo . '</codigo>' .
                    '<' . $tcTipo . '>' . ($llExito ? 'true' : 'false') . '</' . $tcTipo . '>' .
                    '<mensaje>' . htmlspecialchars($tcMensaje, ENT_XML1, 'Windows-1252') . '</mensaje>' .
                '</registro>' .
            '</datos>';

        return $lcXml;
    }

    /**
     * Genera XML para respuesta de error
     *
     * @param string $tcMensaje Mensaje de error
     *
     * @return string XML con el error
     */
    protected function generarXmlError($tcMensaje)
    {
        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<error>' .
                    '<mensaje>' . htmlspecialchars($tcMensaje, ENT_XML1, 'Windows-1252') . '</mensaje>' .
                '</error>' .
            '</datos>';

        return $lcXml;
    }
}