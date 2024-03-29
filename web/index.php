<?php
/**
 * PHP version 7.3
 * web/index.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/controllers.php';

use MiW\Results\Utils;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\RouteCollection;

Utils::loadEnv(__DIR__ . '/../');

// Empleando el componente symfony/config cargamos todas las rutas
$locator = new FileLocator([ __DIR__ . '/../' . $_ENV['CONFIG_DIR'] ]);
$loader  = new YamlFileLoader($locator);
/** @var RouteCollection $routes */
$routes  = $loader->load($_ENV['ROUTES_FILE']);

// obtenermos el contexto de la petición HTTP
$context = new RequestContext(filter_input(INPUT_SERVER, 'REQUEST_URI'));

// Busca la información de la ruta para la petición
$matcher = new UrlMatcher($routes, $context);

// Trabajo hecho. Ahora mostramos la información asociada a la petición
$path_info = filter_input(INPUT_SERVER, 'PATH_INFO') ?? '/';

try {
    $parameters = $matcher->match($path_info);
    $action = $parameters['_controller'];
    // $param1 = $parameters['name'] ?? null;
    $action();   # ejecutar la acción $action()?

    // echo '<pre>', var_dump($parameters), '</pre>';
} catch (ResourceNotFoundException $e) {
    echo 'Caught exception: The resource could not be found' . PHP_EOL;
} catch (MethodNotAllowedException $e) {
    echo 'Caught exception: the resource was found but the request method is not allowed'. PHP_EOL;
}

// El componente también sirve para mostrar la información de una ruta a través de su nombre
// echo '<br>---' . PHP_EOL . '<pre>Inverso "ruta_admin": ';
// var_dump($routes->get('ruta_admin')->getPath());
// echo '</pre>';
