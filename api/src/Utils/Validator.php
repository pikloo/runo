<?php

namespace Runo\Utils;

class Validator
{
    private $data;
    private $requiredFields = [];
    private $errors = [];

    public function __construct()
    {
    }

    public function validate($postData, array $requiredFields = [])
    {
        $this->data = $postData;
        $this->requiredFields = $requiredFields;
        // Common validation rules
        !empty($this->requiredFields) ? $this->validateRequiredFields() : $this->validateNotNull();
        $this->validateEmailFormat();
        $this->validatePasswordFormat();

        return empty($this->errors);
    }

    private function validateRequiredFields()
    {
        foreach ($this->requiredFields as $field) {
            if (empty($this->data[$field])) {
                $this->errors[$field] = "{$field} est obligatoire";
            }
        }
    }

    private function validateNotNull()
    {
        foreach ($this->data as $field => $value) {
            if (empty($value)) {
                $this->errors[$field] = "{$field} ne peut pas être vide";
            }
        }
    }

    private function validateEmailFormat()
    {
        if (isset($this->data['email']) && !filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] =  'Cet email est invalide';
        }
    }

    private function validatePasswordFormat()
    {
        if (!empty($this->data['password'])) {
            $password = filter_var($this->data['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                $this->errors['password'] =  'Le mot de passe doit contenir au moins 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial et faire au moins 8 caractères';
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
