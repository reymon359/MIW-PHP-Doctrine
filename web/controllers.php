<?php

use MiW\Results\Entity\User;
use MiW\Results\Utils;

function funcionHomePage()
{
    global $routes;

    $rutaListado = $routes->get('ruta_user_list')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$rutaListado">Listado Usuarios</a></li>
    </ul>
MARCA_FIN;
}


function funcionListadoUsers(): void
{

    $entityManager = Utils::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->FindAll();
    var_dump($users);
}
