<?php
/**
* fabrica_dao_com.inc.php
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
include_once 'app\modulo\marcas1\dao_com_marcas1.inc.php';

// Implementación de la fábrica DAO concreta para COM.
class fabrica_dao_com extends fabrica_dao {
    public function obtener_dao_marcas1() {
        return new dao_com_marcas1();
    }
}
