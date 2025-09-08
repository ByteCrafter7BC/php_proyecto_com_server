<?php
/**
 * dao.inc.php
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

interface dao {
    public function existe_codigo($tnCodigo);
    public function existe_nombre($tcNombre);
    public function esta_vigente($tnCodigo);
    public function esta_relacionado($tnCodigo);
    public function contar($tcCondicionFiltro);
    public function obtener_nuevo_codigo();
    public function obtener_por_codigo($tnCodigo);
    public function obtener_por_nombre($tcNombre);
    public function obtener_todos($tcCondicionFiltro, $tcOrden);
    public function obtener_dto();
    public function agregar($toDto);
    public function modificar($toDto);
    public function borrar($tnCodigo);
}
