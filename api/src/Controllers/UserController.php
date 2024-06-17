<?php

namespace Runo\Controllers;

use Runo\Models\User;
use Runo\Utils\Validator;

class UserController extends CoreController
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function create()
    {
        $requiredFields = ['firstname', 'lastname', 'email', 'password'];

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if ($data !== null) {
            $data = [
                'firstname' => htmlspecialchars($data['firstname']),
                'lastname' => htmlspecialchars($data['lastname']),
                'email' => htmlspecialchars($data['email']),
                'password' => filter_var($data['password'], FILTER_SANITIZE_SPECIAL_CHARS),
            ];

            $errorsList = [];

            if (!$this->validator->validate($data, $requiredFields)) {
                $errorsList = $this->validator->getErrors();
            }

            if (User::findBy('email', $data['email'])) {
                $errorsList['email'] = 'Cet e-mail est déja utilisé';
            }

            if (count($errorsList) > 0) {
                $this->json_response(503, ['errors' =>  $errorsList]);
            } else {
                $user = new User();
                $user->setFirstname($data['firstname']);
                $user->setLastname($data['lastname']);
                $user->setEmail($data['email']);
                $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
                if ($user->save()) {
                    $data = [
                        'id' => $user->getId(),
                        'firstname' => $user->getFirstname(),
                        'lastname' => $user->getLastname(),
                        'email' => $user->getEmail()
                    ];
                    $this->json_response(201, $data);
                } else {
                    $this->json_response(502, ['error' => 'La sauvegarde a échoué']);
                }
            }
        } else {
            $this->json_response(400,  ['error' => 'invalid JSON data']);
        }
    }

    public function update($userId)
    {
        $user = User::find($userId);
        $user === null && $this->json_response(404, ['error' => 'Utilisateur non trouvé ']);

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if ($data !== null) {
            $errorsList = [];

            if (!$this->validator->validate($data)) {
                $errorsList = $this->validator->getErrors();
            }

            if ($data['email'] && User::findBy('email', $data['email'])) {
                $errorsList['email'] = 'Cet e-mail est déja utilisé';
            }

            if (count($errorsList) > 0) {
                $this->json_response(503, ['errors' =>  $errorsList]);
            } else {
                foreach ($data as $key => $value) {
                    match (true) {
                        $key === 'firstname' => $user->setFirstname(htmlspecialchars($value)),
                        $key === 'lastname' => $user->setLastname(htmlspecialchars($value)),
                        $key === 'email' => $user->setEmail(filter_var($value, FILTER_VALIDATE_EMAIL)),
                        $key === 'password' => $user->setPassword(password_hash(filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT))
                    };
                }
                $user->save() ? $this->json_response(200,  [
                    'id' => $user->getId(),
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'email' => $user->getEmail()
                ]) : $this->json_response(502, ['error' => 'La sauvegarde a échoué']);
            }
        } else {
            $this->json_response(400,  ['error' => 'invalid JSON data']);
        }

    }

    public function read($userId)
    {
        $user = User::find($userId);

        $datas = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
        ];

        $this->json_response(200, $datas);
    }

    public function delete($userId)
    {
    }
}
