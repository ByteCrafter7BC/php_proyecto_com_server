<?php
/**
* dao_com.inc.php
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

include_once 'app\biblioteca\dao.inc.php';

abstract class dao_com implements dao {
    protected $cCom;
    protected $oConexion;

    #---------------------------------------------------------------------------
    public function codigo_existe($tnCodigo) {
        $llExiste = true;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llExiste = $this->oConexion->codigo_existe($tnCodigo);
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<existe>{{existe}}</existe>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $tnCodigo, $lcXml);
        $lcXml = str_replace('{{existe}}', $llExiste ? 'true' : 'false',
            $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function nombre_existe($tcNombre) {
        $llExiste = true;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llExiste = $this->oConexion->nombre_existe($tcNombre);
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<nombre>{{nombre}}</nombre>' .
                    '<existe>{{existe}}</existe>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{nombre}}', $tcNombre, $lcXml);
        $lcXml = str_replace('{{existe}}', $llExiste ? 'true' : 'false',
            $lcXml);
        $lcXml = str_replace('&', '&amp;', $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function esta_vigente($tnCodigo) {
        $llVigente = false;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llVigente = $this->oConexion->esta_vigente($tnCodigo);
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<vigente>{{vigente}}</vigente>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $tnCodigo, $lcXml);
        $lcXml = str_replace('{{vigente}}', $llVigente ? 'true' : 'false',
            $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function esta_relacionado($tnCodigo) {
        $llRelacionado = true;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llRelacionado = $this->oConexion->esta_relacionado($tnCodigo);
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<relacionado>{{relacionado}}</relacionado>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $tnCodigo, $lcXml);
        $lcXml = str_replace('{{relacionado}}',
            $llRelacionado ? 'true' : 'false', $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function contar($tcCondicionFiltro) {
        $lnNumReg = 0;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lnNumReg = $this->oConexion->contar($tcCondicionFiltro);
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<modulo>{{modulo}}</modulo>' .
                    '<numero_registros>' .
                        '{{numero_registros}}' .
                    '</numero_registros>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{modulo}}',
            substr($this->cCom, strpos($this->cCom, '.') + 5), $lcXml);
        $lcXml = str_replace('{{numero_registros}}', $lnNumReg, $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function nuevo_codigo() {
        $lnNuevoCodigo = 0;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lnNuevoCodigo = $this->oConexion->nuevo_codigo();
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<modulo>{{modulo}}</modulo>' .
                    '<nuevo_codigo>{{nuevo_codigo}}</nuevo_codigo>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{modulo}}',
            substr($this->cCom, strpos($this->cCom, '.') + 5), $lcXml);
        $lcXml = str_replace('{{nuevo_codigo}}', $lnNuevoCodigo, $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function obtener_por_codigo($tnCodigo) {
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lcXml = $this->convertir_dto_a_xml(
                    $this->oConexion->obtener_por_codigo($tnCodigo));
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function obtener_por_nombre($tcNombre) {
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lcXml = $this->convertir_dto_a_xml(
                    $this->oConexion->obtener_por_nombre($tcNombre));
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function obtener_todos($tcCondicionFiltro, $tcOrden) {
        $lcXml = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $lcXml = $this->oConexion->obtener_todos(
                    $tcCondicionFiltro, $tcOrden);
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function obtener_dto() {
        $loDto = null;

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $loDto = $this->oConexion->obtener_dto();
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        return $loDto;
    }

    #---------------------------------------------------------------------------
    public function agregar($toDto) {
        $llAgregado = false;
        $lcMensaje = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llAgregado = $this->oConexion->agregar($toDto);

                if (!$llAgregado) {
                    $lcMensaje = $this->oConexion->obtener_ultimo_error();
                }
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<agregado>{{agregado}}</agregado>' .
                    '<mensaje>{{mensaje}}</mensaje>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $toDto->obtener_codigo(), $lcXml);
        $lcXml = str_replace('{{agregado}}', $llAgregado ? 'true' : 'false',
            $lcXml);
        $lcXml = str_replace('{{mensaje}}', $lcMensaje, $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function modificar($toDto) {
        $llModificado = false;
        $lcMensaje = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llModificado = $this->oConexion->modificar($toDto);

                if (!$llModificado) {
                    $lcMensaje = $this->oConexion->obtener_ultimo_error();
                }
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<modificado>{{modificado}}</modificado>' .
                    '<mensaje>{{mensaje}}</mensaje>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $toDto->obtener_codigo(), $lcXml);
        $lcXml = str_replace('{{modificado}}', $llModificado ? 'true' : 'false',
            $lcXml);
        $lcXml = str_replace('{{mensaje}}', $lcMensaje, $lcXml);

        return $lcXml;
    }

    #---------------------------------------------------------------------------
    public function borrar($tnCodigo) {
        $llBorrado = false;
        $lcMensaje = '';

        try {
            $this->conectar();

            if (isset($this->oConexion)) {
                $llBorrado = $this->oConexion->borrar($tnCodigo);

                if (!$llBorrado) {
                    $lcMensaje = $this->oConexion->obtener_ultimo_error();
                }
            }
        } catch (Exception $ex) {
            print 'ERROR: ' . $ex->getMessage() . '<br>';
        }

        $this->desconectar();

        $lcXml = '<?xml version="1.0" encoding="Windows-1252"?>' .
            '<datos>' .
                '<registro>' .
                    '<codigo>{{codigo}}</codigo>' .
                    '<borrado>{{borrado}}</borrado>' .
                    '<mensaje>{{mensaje}}</mensaje>' .
                '</registro>' .
            '</datos>';

        $lcXml = str_replace('{{codigo}}', $tnCodigo, $lcXml);
        $lcXml = str_replace('{{borrado}}', $llBorrado ? 'true' : 'false',
            $lcXml);
        $lcXml = str_replace('{{mensaje}}', $lcMensaje, $lcXml);

        return $lcXml;
    }

    /** ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ *
    *                            PROTECTED METHODS                            *
    * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

    #---------------------------------------------------------------------------
    protected function conectar() {
        if (isset($this->cCom) && !empty($this->cCom)) {
            try {
                // $this->oConexion = new COM($this->cCom, NULL, CP_UTF8);
                $this->oConexion = new COM($this->cCom, NULL, CP_ACP);
            } catch (Exception $ex) {
                print 'ERROR: ' . $ex->getMessage() . '<br>';
                die();
            }
        }
    }

    #---------------------------------------------------------------------------
    protected function desconectar() {
        if (isset($this->oConexion)) {
            $this->oConexion = null;
        }
    }

    #---------------------------------------------------------------------------
    protected function convertir_dto_a_xml($toDto) {
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
        $lcXml = str_replace('{{nombre}}', $toDto->obtener_nombre(), $lcXml);
        $lcXml = str_replace('{{vigente}}',
            $toDto->esta_vigente() ? 'true' : 'false', $lcXml);
        $lcXml = str_replace('&', '&amp;', $lcXml);

        return $lcXml;
    }
}
