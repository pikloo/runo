<?php

/************************************* 
             Sécurity
/************************************/

$router->map(
    'POST',
    '/login',
    [
        'action' => 'getToken',
        'controller' => 'SecurityController',
    ],
    'getToken'
);

/************************************* 
             USERS
/************************************/

$router->map(
    'POST',
    '/users',
    [
        'action' => 'create',
        'controller' => 'UserController',
    ],
    'createUser'
);

$router->map(
    'GET',
    '/me',
    [
        'action' => 'read',
        'controller' => 'UserController',
    ],
    'readCurrentUser'
);

$router->map(
    'GET',
    '/users/[i:id]',
    [
        'action' => 'read',
        'controller' => 'UserController',
    ],
    'readUser'
);

$router->map(
    'PATCH',
    '/users/[i:id]',
    [
        'action' => 'update',
        'controller' => 'UserController',
    ],
    'updateUser'
);

$router->map(
    'DELETE',
    '/users/[i:id]',
    [
        'action' => 'delete',
        'controller' => 'UserController',
    ],
    'deleteUser'
);

// Les courses d'un user
$router->map(
    'GET',
    '/users/[i:id]/races',
    [
        'action' => 'read',
        'controller' => 'UserController',
    ],
   'readUserRaces'
);



/************************************* 
             RACE
/************************************/


$router->map(
    'POST',
    '/races',
    [
        'action' => 'create',
        'controller' => 'RaceController',
    ],
    'createRace'
);

$router->map(
    'GET',
    '/races/[i:id]',
    [
        'action' => 'read',
        'controller' => 'RaceController',
    ],
    'readRace'
);

$router->map(
    'PATCH',
    '/races/[i:id]',
    [
        'action' => 'update',
        'controller' => 'RaceController',
    ],
    'updateRace'
);

$router->map(
    'DELETE',
    '/races/[i:id]',
    [
        'action' => 'delete',
        'controller' => 'RaceController',
    ],
    'deleteRace'
);

$router->map(
    'GET',
    '/races',
    [
        'action' => 'read',
        'controller' => 'RaceController',
    ],
    'readRaces'
);

// Les commentaires de la course
$router->map(
    'GET',
    '/races/[i:id]/comments',
    [
        'action' => 'read',
        'controller' => 'CommentController',
    ],
   'readComments'
);

/************************************* 
             RACE TYPE
/************************************/


$router->map(
    'POST',
    '/race-types',
    [
        'action' => 'create',
        'controller' => 'RaceTypeController',
    ],
    'createRaceType'
);

$router->map(
    'GET',
    '/race-types/[i:id]',
    [
        'action' => 'read',
        'controller' => 'RaceTypeController',
    ],
    'readRaceType'
);



$router->map(
    'PATCH',
    '/race-types/[i:id]',
    [
        'action' => 'update',
        'controller' => 'RaceTypeController',
    ],
    'updateRaceType'
);

$router->map(
    'DELETE',
    '/race-types/[i:id]',
    [  
        'action' => 'delete',
        'controller' => 'RaceTypeController',
    ],
    'deleteRaceType'
);