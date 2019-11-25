<?php
/**
 * PHP version 7.3
 * src\update_result.php
 *
 * @category Utils
 * @package  MiW\Results
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utils;

require __DIR__ . '/../vendor/autoload.php';

// Carga las variables de entorno
Utils::loadEnv(__DIR__ . '/../');

$entityManager = Utils::getEntityManager();

if ($argc < 3 || $argc > 4) {

    $fich = basename(__FILE__);
    echo <<< MARCA_FIN
    Se necesitan 4 parametros: [ResultID] [Result] [UserID] [Timestamp]
    Uso: $fich <ResultID> <Result> <UserId> [<Timestamp>]
MARCA_FIN;

    exit(0);
}

$resultId = (int) $argv[1];
/** @var Result $result */
$result = $entityManager
    ->getRepository(Result::class)
    ->findOneBy(['id' => $resultId]);
if (null === $result) {
    echo "Resultado $resultId no encontrado" . PHP_EOL;
    exit(0);
}

$userId = (int) $argv[3];
/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['id' => $userId]);
if (null === $user) {
    echo "Usuario $userId no encontrado" . PHP_EOL;
    exit(0);
}

$result->set

$newTimestamp = $argv[3] ?? new DateTime('now');



$result = new Result($newResult, $user, $newTimestamp);
try {
    $entityManager->persist($result);
    $entityManager->flush();
    echo 'Created Result with ID ' . $result->getId()
        . ' USER ' . $user->getUsername() . PHP_EOL;
} catch (Exception $exception) {
    echo $exception->getMessage();
}
