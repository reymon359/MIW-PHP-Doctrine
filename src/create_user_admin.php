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

if ($argc < 4 || $argc > 6) {

    $fich = basename(__FILE__);
    echo <<< MARCA_FIN
    Se necesitan estos parametros: [nombre] [email] [password] [enabled] [isAdmin]
    Uso: $fich <nombre> <email> <password> [<enabled>] [<isAdmin>] 
MARCA_FIN;

    exit(0);

}

$user = new User();
$user->setUsername((string)$argv[1] ?? $_ENV['ADMIN_USER_NAME']);
$user->setEmail((string)$argv[2] ?? $_ENV['ADMIN_USER_EMAIL']);
$user->setPassword((string)$argv[3] ?? $_ENV['ADMIN_USER_PASSWD']);
$user->setEnabled($argc >= 5 ? (int)$argv[4] : true);
$user->setIsAdmin($argc >= 6 ? (int)$argv[5] : true);

try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo 'Created Admin User with ID #' . $user->getId() . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
