<?php

/**
 * PHP version 7.3
 * src/controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utils;

function funcionHomePage()
{
    global $routes;

    $rutaListadoUsers = $routes->get('ruta_user_list')->getPath();
    echo <<< ____MARCA_FIN
    <ul>
        <li><a href="$rutaListadoUsers">Listado Users</a></li>
    </ul>
____MARCA_FIN;
}

function funcionListadoUsers(): void
{
    global $routes;

    $entityManager = Utils::getEntityManager();
    $usersRepository = $entityManager->getRepository(User::class);
    $users = $usersRepository->findAll();
    var_dump($users);

    // Enlace Nuevo User
    $rutaNuevoUser = $routes->get('ruta_user_nuevo')->getPath();
    echo "<a href='$rutaNuevoUser'>Nuevo User</a>";
}

function funcionNuevoUser()
{
    global $routes, $context;
    var_dump($context);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') { // método GET => muestro formulario
        $rutaNuevoUser = $routes->get('ruta_user_nuevo')->getPath();
        echo <<< ___MARCA_FIN
    <form method="POST" action="$rutaNuevoUser">
        Nombre: <input type="text" name="nombre" required>
        Email: <input type="email" name="email" required>
        Password: <input type="password" name="password" required>
        Enabled: <input type="checkbox" name="enabled" value="true"> 
        isAdmin: <input type="checkbox" name="isAdmin" value="true"> 
        <input type="submit" value="Enviar"> 
    </form>
___MARCA_FIN;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {    // método POST => proceso formulario
        $entityManager = Utils::getEntityManager();

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $enabled = $_POST['enabled'] ?? true;
        $isAdmin = $_POST['isAdmin'] ?? true;

        $user = new User($nombre, $email, $password, $enabled, $isAdmin);

        // Hacer persistente los datos
        $entityManager->persist($user);
        $entityManager->flush();
        var_dump($user);

        // Enlace Listado Users
        $rutaListadoUsers = $routes->get('ruta_user_list')->getPath();
        echo "<a href='  $rutaListadoUsers'>Listado Users</a>";
    }
}