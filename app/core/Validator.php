<?php

namespace Warkim\core;

class Validator
{
    public static function make($data, $rules)
    {

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        $errors = [];

        // Loop melalui setiap aturan yang diberikan
        foreach ($rules as $key => $keyRules) {
            $keyRules = is_array($keyRules) ? (array)$keyRules : explode('|', $keyRules); // Pastikan aturan berupa array
            foreach ($keyRules as $rule) {

                if ($rule === 'required' && (!isset($data[$key]) || empty($data[$key]))) {
                    $errors[$key][] = "The $key field is required.";
                } elseif ($rule === 'email' && !filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
                    $errors[$key][] = "The $key field must be a valid email address.";
                } elseif ($rule === 'string' && !is_string($data[$key])) {
                    $errors[$key][] = "The $key field must be a string.";
                } elseif ($rule === 'numeric' && !is_numeric($data[$key])) {
                    $errors[$key][] = "The $key field must be numeric.";
                } elseif ($rule === 'integer' && !is_integer($data[$key])) {
                    $errors[$key][] = "The $key field must be integer.";
                } elseif ($rule === 'boolean' && !is_bool($data[$key])) {
                    $errors[$key][] = "The $key field must be a boolean.";
                } elseif ($rule === 'array' && !is_array($data[$key])) {
                    $errors[$key][] = "The $key field must be an array.";
                }

                // Tambahkan aturan validasi lain di sini (misalnya, string, numeric, dll.)
            }
        }

        // Kembalikan instance ValidatorResult dengan data dan error
        return new ValidatorResult(empty($errors), $errors);
    }
}

class ValidatorResult
{
    private $valid;
    private $errors;

    public function __construct($valid, $errors)
    {
        $this->valid = $valid;
        $this->errors = $errors;
    }

    public function fails()
    {
        return !$this->valid;
    }

    public function errors()
    {
        return $this->errors;
    }
}
