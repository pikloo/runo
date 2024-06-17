<?php

namespace Runo\Controllers;

use UnexpectedValueException;

abstract class CoreController
{
    protected $router;
    
    public function __construct()
    {
        global $router;
        $this->router = $router;
        $this->configureAcl();
    }

    /**
     * Gestion de l'enevoie de la réponse et de son status code HTTP
     * Supprime l'ancien entête et le remplace avec le status code fourni
     * Force le cache et renvoie une réponse avec le message
     *
     * @param integer $code
     * @param [type] $message
     * @return void
     */
    public function json_response($code, $message, $options = [])
    {
        $domain_url = getenv('DOMAIN_URL', true) ?: getenv('DOMAIN_URL');
        $app_port = getenv('APP_PORT', true) ?: getenv('APP_PORT');

        header_remove();
        http_response_code($code);
        header("Access-Control-Allow-Origin: " . $domain_url . ':' . $app_port);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PATCH');
        header('Access-Control-Allow-Credentials: true');

        $status = array(
            200 => '200 OK',
            201 => '201 Created',
            202 => '202 Accepted',
            203 => '203 Non-Authoritative Information',
            204 => '204 No Content',
            205 => '205 Reset Content',
            400 => '400 Bad Request',
            401 => '401 Unauthorized',
            402 => '402 Payment Required',
            403 => '403 Forbidden',
            404 => '404 Not Found',
            405 => '405 Method Not Allowed',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error',
            501 => '501 Not Implemented',
            502 => '502 Bad Gateway',
            503 => '503 Service Unavailable',
            504 => '504 Gateway Timeout',
            505 => '505 HTTP Version Not Supported',
        );

        header('Status:' . $status[$code]);

        if (!empty($options)) {
            foreach ($options as $option => $value) {
                header("$option: $value");
            };
        }

        echo json_encode($message);

        exit();
    }


    /**
     * Configure Acl: Paramétrage des restrictions d'accès à certaines routes en fonction des rôles
     *
     * @return void
     */
    private function configureAcl()
    {
        $acl = [
            'readUser' => ['ROLE_USER'],
            'readCurrentUser' => ['ROLE_USER'],
            'updateUser' => ['ROLE_USER'],
            'deleteUser' => ['ROLE_USER'],
            'createRace' => ['ROLE_USER'],
            'readRace' => ['ROLE_USER'],
            'readRaces' => ['ROLE_USER'],
            'updateRace' => ['ROLE_USER'],
            'deleteRace' => ['ROLE_USER'],
            'createRaceType' => ['ROLE_USER'],
            'updateRaceType' => ['ROLE_USER'],
            'deleteRaceType' => ['ROLE_USER'],
            'readRaceType' => ['ROLE_USER'],
            'createComment' => ['ROLE_USER'],
            'updateComment' => ['ROLE_USER'],
            'deleteComment' => ['ROLE_USER'],
            'readComments' => ['ROLE_USER'],
        ];

        $match = $this->router->match();
        if ($match) {
            $currentRouteName = $match['name'];

            if (array_key_exists($currentRouteName, $acl)) {
                $authorizedRoles = $acl[$currentRouteName];
                $this->checkRoleAuthorization($authorizedRoles);
            }
        }
    }

    /**
     * Check Role Authorization: Vérifie si le rôle de l'utilisateur connecté est dans le tableau de rôle donné en paramètre
     * 
     * Si la vérification échoue une réponse 403 est affichée
     * 
     * @param array $roles
     * @return void
     */
    private function checkRoleAuthorization($roles = [])
    {
        // if (!isset($_COOKIE['jwt'])) {
        //     $this->json_response(403, ['error' => 'Le cookie d\'authentification est manquant']);
        //     exit();
        // } else {
        //     $jwt = $_COOKIE['jwt'];
        //     $secretKey  = getenv('JWT_SECRET_KEY');
        //     try {
        //         $decoded = JWT::decode($jwt, new Key($secretKey, 'HS512'));
        //         $payload = json_decode(json_encode($decoded), true);

        //         if (in_array($payload['role'], $roles)) {
        //             return true;
        //         } else {
        //             $this->json_response(403, ['error' => 'Accès non autorisé']);
        //             exit();
        //         }
        //     } catch (UnexpectedValueException $e) {
        //         $this->json_response(500, ['error' => $e]);
        //     }
        // }
    }
}
