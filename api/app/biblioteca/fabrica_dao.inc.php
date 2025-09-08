<?php
/**
 * fabrica_dao.inc.php
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

include_once 'app\biblioteca\fabrica_dao_com.inc.php';
include_once 'app\prog\constantes.inc.php';

abstract class fabrica_dao {
    // Lista de tipos de DAO admitidos por la fábrica.
    // BD_COM      1
    // BD_FIREBIRD 2
    // BD_MYSQL    3
    // BD_POSTGRES 4

    // Habrá un método para cada DAO que se pueda crear.
    // Las fábricas concretas deberán implementar estos métodos.
    public function obtener_dao_barrios() {}
    public function obtener_dao_ciudades() {}
    public function obtener_dao_cobrador() {}
    public function obtener_dao_depar() {}
    public function obtener_dao_familias() {}
    public function obtener_dao_maquinas() {}
    public function obtener_dao_marcas1() {}
    public function obtener_dao_marcas2() {}
    public function obtener_dao_modelos() {}
    public function obtener_dao_proceden() {}
    public function obtener_dao_rubros1() {}
    public function obtener_dao_rubros2() {}
    public function obtener_dao_vendedor() {}

    public static function obtener_fabrica_dao($tnCualFabrica = 0) {
        # inicio { validaciones del parámetro }
        if (func_num_args() < 1) {
            return false;
        }

        if (
            !is_integer($tnCualFabrica)
            || !($tnCualFabrica >= 1 && $tnCualFabrica <= 4)
        ) {
            return false;
        }
        # fin { validaciones del parámetro }

        $lcFabricaDao = $loFabricaDao = false;

        switch ($tnCualFabrica) {
        case BD_COM:
            $lcFabricaDao = 'fabrica_dao_com';
            break;
        case BD_FIREBIRD:
            $lcFabricaDao = 'fabrica_dao_firebird';
            break;
        case BD_MYSQL:
            $lcFabricaDao = 'fabrica_dao_mysql';
            break;
        case BD_POSTGRES:
            $lcFabricaDao = 'fabrica_dao_postgres';
            break;
        }

        if (is_string($lcFabricaDao) && trim($lcFabricaDao) !== '') {
            try {
                $loFabricaDao = new $lcFabricaDao;
            } catch (Exception $ex) {
                print 'ERROR: ' . $ex->getMessage() . '<br>';
            }
        }

        return $loFabricaDao;
    }
}
