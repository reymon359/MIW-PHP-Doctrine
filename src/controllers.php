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
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";

    $rutaListadoUsers = $path . $routes->get('ruta_user_list')->getPath();
    $rutaNuevoUser = $path . $routes->get('ruta_user_nuevo')->getPath();
    $rutaListadoResults = $path . $routes->get('ruta_result_list')->getPath();
    $rutaNuevoResultado = $path . $routes->get('ruta_result_nuevo')->getPath();

    echo <<< ____MARCA_FIN
<div style="text-align: center">

<h2 >Resultados Doctrine</h2>
    <ul style="list-style-type: none;">
        <li><a href="$rutaListadoUsers">Listado Users</a></li>
        <li><a href="$rutaNuevoUser">Nuevo user</a></li>
        <li><a href="$rutaListadoResults">Listado Resultados</a></li>
        <li><a href="$rutaNuevoResultado">Nuevo Resultado</a></li>
    </ul>
</div>
____MARCA_FIN;
    displayFooter();

}

function funcionListadoUsers(): void
{
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaNuevoUser = $path . $routes->get('ruta_user_nuevo')->getPath();
    $rutaRaiz = $path . $routes->get('ruta_raíz')->getPath();

    $entityManager = Utils::getEntityManager();
    $usersRepository = $entityManager->getRepository(User::class);
    $users = $usersRepository->findAll();
//    var_dump($users);

    echo <<< ___MARCA_FIN
<h2 style="text-align: center">Lista Usuarios</h2>
<table style="width:50%; margin:auto">
  <tr>
    <th>UserId</th>
    <th>Nombre</th>
    <th>Email</th>
    <th>Enabled</th>
    <th>IsAdmin</th>
  </tr>
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

    displayFooter();
}


function funcionNuevoUser()
{
    global $routes, $context;
//    var_dump($context);
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaNuevoUser = $path . $routes->get('ruta_user_nuevo')->getPath();
    $rutaListadoUsers = $path . $routes->get('ruta_user_list')->getPath();
    $rutaRaiz = $path . $routes->get('ruta_raíz')->getPath();

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
 
         <div style='text-align: center'>
                <a href='$rutaRaiz'>Inicio</a>
                <a href='$rutaListadoUsers'>Listado Users</a>
            </div>
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
        displayFooter();
    }
}

function funcionListadoResultados(): void
{
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaNuevoResult = $path . $routes->get('ruta_result_nuevo')->getPath();
    $rutaRaiz = $path . $routes->get('ruta_raíz')->getPath();

    $entityManager = Utils::getEntityManager();
    $resultsRepository = $entityManager->getRepository(Result::class);
    $results = $resultsRepository->findAll();
//    var_dump($results);

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

    displayFooter();
}

function funcionNuevoResult()
{
    global $routes, $context;
//    var_dump($context);
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";
    $rutaNuevoResult = $path . $routes->get('ruta_result_nuevo')->getPath();
    $rutaListadoResults = $path . $routes->get('ruta_result_list')->getPath();
    $rutaRaiz = $path . $routes->get('ruta_raíz')->getPath();


    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        echo <<< ___MARCA_FIN
<h2 style="text-align: center">Nuevo resultado</h2>
     <form style="text-align: center" method="POST" action="$rutaNuevoResult">
        Resultado: <input type="number" name="result" required><br><br>
        UserId: <input type="number" name="userId" required><br><br>
        TimeStamp: <input type="date" name="timeStamp" ><br><br>
        <input type="submit" value="Enviar"> 
    </form>
   <div style='text-align: center'>
                <a href='$rutaRaiz'>Inicio</a>
                <a href='$rutaListadoResults'>Listado Results</a>
            </div>
___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        $entityManager = Utils::getEntityManager();

        $newResult = $_POST['result'];
        $userId = $_POST['userId'];
        $timeStamp = isset($_POST['timeStamp']) ? new DateTime($_POST['timeStamp']) : new DateTime('now');

    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['id' => $userId]);
    if (null === $user) {
        echo "Usuario $userId no encontrado" . PHP_EOL;
        exit(0);
    }

        $result = new Result($newResult, $user, $timeStamp);

        // Hacer persistente los datos
        $entityManager->persist($result);
        $entityManager->flush();
//        var_dump($result);

        echo "<h2 style=\"text-align: center\">Resultado $newResult creado!</h2>";

        displayFooter();
    }
}

function displayFooter(){
    global $routes;
    $path = explode("index.php", $_SERVER['REQUEST_URI'])[0] . "index.php";

    $rutaListadoUsers = $path . $routes->get('ruta_user_list')->getPath();
    $rutaNuevoUser = $path . $routes->get('ruta_user_nuevo')->getPath();
    $rutaListadoResults = $path . $routes->get('ruta_result_list')->getPath();
    $rutaNuevoResultado = $path . $routes->get('ruta_result_nuevo')->getPath();

    echo <<< ____MARCA_FIN
    <ul style="list-style-type: none;display: flex;flex-wrap: wrap;">
        <li><a href="$rutaListadoUsers">Listado Users</a></li>&nbsp;
        <li><a href="$rutaNuevoUser">Nuevo user</a></li>&nbsp;
        <li><a href="$rutaListadoResults">Listado Resultados</a></li>&nbsp;
        <li><a href="$rutaNuevoResultado">Nuevo Resultado</a></li>&nbsp;
    </ul>
____MARCA_FIN;
}