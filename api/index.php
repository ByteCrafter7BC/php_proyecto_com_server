<?php
/**
* index.php
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

$laComponentesUrl = parse_url($_SERVER['REQUEST_URI']);
$lcRuta = $laComponentesUrl['path'];
$laPartesRuta = explode('/', $lcRuta);
$laPartesRuta = array_filter($laPartesRuta);
$laPartesRuta = array_slice($laPartesRuta, 0);
$lcRutaElegida = 'app/controlador/404.php';

if ($laPartesRuta[0] == 'com_interop' && $laPartesRuta[1] == 'api') {
    if (count($laPartesRuta) == 4 or count($laPartesRuta) == 5) {
        $lcModulo = $laPartesRuta[2];
        $lcMetodo = $laPartesRuta[3];
        $lcParametro = null;
    }

    if (count($laPartesRuta) == 5) {
        $lcParametro = mb_convert_encoding(urldecode($laPartesRuta[4]),
            'Windows-1252', 'UTF-8');
    }

    switch ($lcModulo) {
        case 'marcas1':
            $lcRutaElegida = 'app/controlador/marcas1_controlador.php';
            break;
    }
}

include_once $lcRutaElegida;
