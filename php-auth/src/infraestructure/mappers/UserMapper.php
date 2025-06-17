<?php

namespace app\infraestructure\mappers;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\domain\entities\User;
use app\domain\errors\CustomError;

class UserMapper
{
    public  static function userEntityFromObject(object $object)
    {
        $id = $object->id;
        // $_id = $object->_id;
        $name = $object->name;
        $email = $object->email;
        $password = $object->password;
        $roles = $object->roles;

        if (!$name) throw CustomError::badRequest("Missing name");
        if (!$email) throw CustomError::badRequest("Missing email");
        if (!$password)  throw CustomError::badRequest("Missing password");
        if (!$roles) throw CustomError::badRequest("Missing roles");

        return new User(
            $_id ?? $id,
            $name,
            $email,
            $password,
            $roles
        );
    }
}
