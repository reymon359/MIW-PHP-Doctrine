<?php

/**
 * PHP version 7.3
 * src/controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utils;

function funcionHomePage()
{
    displayNavbar();

    echo <<< ____MARCA_FIN
<div style="text-align: center">

<h2 >Resultados Doctrine</h2>
<p>CRUD para usuarios y resultados</p>
<p><a href="https://ramonmorcillo.com" target="_blank">Ramón Morcillo Cascales</a></p>
</div>
____MARCA_FIN;

}

function funcionListadoUsers(): void
{
    displayNavbar();
    $entityManager = Utils::getEntityManager();
    $usersRepository = $entityManager->getRepository(User::class);
    $users = $usersRepository->findAll();
//    var_dump($users);

    echo <<< ___MARCA_FIN
<h2 style="text-align: center">Lista Usuarios</h2>
<table style="width:50%; margin:auto">
  <tr>    <th>UserId</th>    <th>Nombre</th>    <th>Email</th>
    <th>Enabled</th>    <th>IsAdmin</th>  </tr>
___MARCA_FIN;

    foreach ($users as $user) {
        $userId = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $enabled = $user->isEnabled() ? "si" : "no";
        $isAdmin = $user->isAdmin() ? "si" : "no";

        echo <<< ___MARCA_FIN
  <tr>
    <td>$userId</td>
    <td>$username</td>
    <td>$email</td>
    <td>$enabled</td>
    <td>$isAdmin</td>
  </tr>
___MARCA_FIN;
    }
    echo <<< ___MARCA_FIN
 </table>
___MARCA_FIN;

}


function funcionNuevoUser()
{
    displayNavbar();
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaNuevoUser = $path . $routes->get('ruta_user_nuevo')->getPath();


    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        echo <<< ___MARCA_FIN
<h2 style="text-align: center">Nuevo usuario</h2>
    <form style="text-align: center" method="POST" action="$rutaNuevoUser">
        Nombre: <input type="text" name="nombre" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Enabled: <input type="checkbox" name="enabled" checked> 
        isAdmin: <input type="checkbox" name="isAdmin" checked> <br><br>
        <input type="submit" value="Enviar"> 
    </form>
       
___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        $entityManager = Utils::getEntityManager();

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $enabled = isset($_POST['enabled']) ? $_POST['enabled'] : false;
        $isAdmin = isset($_POST['isAdmin']) ? $_POST['isAdmin'] : false;

        $user = new User($nombre, $email, $password, $enabled, $isAdmin);

        // Hacer persistente los datos
        $entityManager->persist($user);
        $entityManager->flush();
//        var_dump($user);
        echo "<h2 style=\"text-align: center\">Usuario $nombre creado!</h2>";

    }
}

function funcionEliminarUser()
{
    displayNavbar();
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaEliminarUser = $path . $routes->get('ruta_user_eliminar')->getPath();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        echo <<< ___MARCA_FIN
<h2 style="text-align: center">Eliminar usuario</h2>
<p style="text-align: center">Introduce el id del usuario que deseas eliminar</p>
    <form style="text-align: center" method="POST" action="$rutaEliminarUser">
        ID de usuario: <input type="number" name="userId" required><br><br>
        <input type="submit" value="Enviar"> 
    </form>
       
___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        $entityManager = Utils::getEntityManager();
        $userId = $_POST['userId'];

        /** @var User $user */
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['id' => $userId]);
        if (null === $user) {
            echo "<h2 style=\"text-align: center\">Usuario con id $userId no encontrado</h2>";
            exit(0);
        }
        try {
            $entityManager->remove($user);
            $entityManager->flush();
            echo "<h2 style=\"text-align: center\">Usuario con id $userId eliminado</h2>";
        } catch (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}

function funcionListadoResultados(): void
{
    displayNavbar();

    $entityManager = Utils::getEntityManager();
    $resultsRepository = $entityManager->getRepository(Result::class);
    $results = $resultsRepository->findAll();

    echo <<< ___MARCA_FIN
<h2 style="text-align: center">Lista Resultados</h2>
<table style="width:50%; margin:auto">
  <tr>
    <th>ResultId</th>
    <th>Resultado</th>
    <th>Usuario</th>
    <th>Tiempo</th>
  </tr>
___MARCA_FIN;

    foreach ($results as $result) {
        $resultId = $result->getId();
        $resultInfo = $result->getResult();
        $userName = $result->getUser();
        $time = $result->getTime()->format('Y-m-d H:i:s');
        echo <<< ___MARCA_FIN
  <tr>
    <td>$resultId</td>
    <td>$resultInfo</td>
    <td>$userName</td>
    <td>$time</td>
  </tr>
___MARCA_FIN;
    }
    echo <<< ___MARCA_FIN
 </table>
___MARCA_FIN;


}

function funcionNuevoResult()
{
    displayNavbar();

    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaNuevoResult = $path . $routes->get('ruta_result_nuevo')->getPath();


    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        echo <<< ___MARCA_FIN
<h2 style="text-align: center">Nuevo resultado</h2>
     <form style="text-align: center" method="POST" action="$rutaNuevoResult">
        Resultado: <input type="number" name="result" required><br><br>
        UserId: <input type="number" name="userId" required><br><br>
        TimeStamp: <input type="date" name="timeStamp" ><br><br>
        <input type="submit" value="Enviar"> 
    </form>

___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        $entityManager = Utils::getEntityManager();

        $newResult = $_POST['result'];
        $userId = $_POST['userId'];
        try {
            $timeStamp = isset($_POST['timeStamp']) ? new DateTime($_POST['timeStamp']) : new DateTime('now');
        } catch (Exception $e) {
            echo $e;
        }

        /** @var User $user */
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['id' => $userId]);
        if (null === $user) {
            echo "<h2 style=\"text-align: center\">Usuario con id $userId no encontrado</h2>" . PHP_EOL;
            exit(0);
        }
        $result = new Result($newResult, $user, $timeStamp);
        // Hacer persistente los datos
        $entityManager->persist($result);
        $entityManager->flush();
//        var_dump($result);
        echo "<h2 style=\"text-align: center\">Resultado $newResult creado!</h2>";

    }
}

function funcionEliminarResultado()
{
    displayNavbar();
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaEliminarResultado = $path . $routes->get('ruta_result_eliminar')->getPath();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        echo <<< ___MARCA_FIN
<h2 style="text-align: center">Eliminar resultado</h2>
<p style="text-align: center">Introduce el id del resultado que deseas eliminar</p>
    <form style="text-align: center" method="POST" action="$rutaEliminarResultado">
        ID de resultado: <input type="number" name="resultId" required><br><br>
        <input type="submit" value="Enviar"> 
    </form>
       
___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        $entityManager = Utils::getEntityManager();
        $resultId = $_POST['resultId'];

        /** @var Result $result */
        $result = $entityManager
            ->getRepository(Result::class)
            ->findOneBy(['id' => $resultId]);
        if (null === $result) {
            echo "<h2 style=\"text-align: center\">Resultado con id $resultId no encontrado</h2>" . PHP_EOL;
            exit(0);
        }

        try {
            $entityManager->remove($result);
            $entityManager->flush();
            echo "<h2 style=\"text-align: center\">Resultado con id $resultId eliminado</h2>";
        } catch (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}

function displayNavbar()
{
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaRaiz = $path . $routes->get('ruta_raíz')->getPath();
    $rutaListadoUsers = $path . $routes->get('ruta_user_list')->getPath();
    $rutaNuevoUser = $path . $routes->get('ruta_user_nuevo')->getPath();
    $rutaEliminarUser = $path . $routes->get('ruta_user_eliminar')->getPath();
    $rutaListadoResults = $path . $routes->get('ruta_result_list')->getPath();
    $rutaNuevoResultado = $path . $routes->get('ruta_result_nuevo')->getPath();
    $rutaEliminarResultado = $path . $routes->get('ruta_result_eliminar')->getPath();
    echo <<< ____MARCA_FIN
    <ul style="list-style-type: none;display: flex;flex-wrap: wrap;justify-content: space-evenly;align-items: center;">
        <li><a href="$rutaRaiz">Inicio</a></li>
        <li><a href="$rutaListadoUsers">Listado Usuarios</a></li>
        <li><a href="$rutaNuevoUser">Nuevo Usuarios</a></li>
        <li><a href="$rutaEliminarUser">Eliminar Usuario</a></li>
        <li><a href="$rutaListadoResults">Listado Resultados</a></li>
        <li><a href="$rutaNuevoResultado">Nuevo Resultado</a></li>
        <li><a href="$rutaEliminarResultado">Eliminar Resultado</a></li>

    </ul>
____MARCA_FIN;
}