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
        $lcXml = str_replace('{{existe}}', $llExiste, $lcXml);

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
        $lcXml = str_replace('{{existe}}', $llExiste, $lcXml);
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
        $lcXml = str_replace('{{vigente}}', $llVigente, $lcXml);

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
        $lcXml = str_replace('{{agregado}}', $llAgregado, $lcXml);
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
        $lcXml = str_replace('{{modificado}}', $llModificado, $lcXml);
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
        $lcXml = str_replace('{{borrado}}', $llBorrado, $lcXml);
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
        $lcXml = str_replace('{{vigente}}', $toDto->esta_vigente(), $lcXml);
        $lcXml = str_replace('&', '&amp;', $lcXml);

        return $lcXml;
    }
}
