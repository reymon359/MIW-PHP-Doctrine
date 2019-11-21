<?php
/**
 * PHP version 7.3
 * src\create_user_admin.php
 *
 * @category Utils
 * @package  MiW\Results
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utils;

require __DIR__ . '/../vendor/autoload.php';

// Carga las variables de entorno
Utils::loadEnv(__DIR__ . '/../');

$entityManager = Utils::getEntityManager();

if ($argc < 5 || $argc > 6) {
    echo  "Introduce solo 5 parametros: nombre, email, password, enabled, isAdmin";
    exit(0);

}

$user = new User();
$user->setUsername((string)$argv[1] ?? $_ENV['ADMIN_USER_NAME']);
$user->setEmail((string)$argv[2] ?? $_ENV['ADMIN_USER_EMAIL']);
$user->setPassword((string)$argv[3] ?? $_ENV['ADMIN_USER_PASSWD']);
$user->setEnabled((boolean)$argv[4] ?? true);
$user->setIsAdmin((boolean)$argv[5] ?? true);

try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo 'Created Admin User with ID #' . $user->getId() . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
