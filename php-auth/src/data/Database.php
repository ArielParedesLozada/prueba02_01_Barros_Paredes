<?php
namespace app\data;

abstract class Database
{
    public abstract static function connect(array $options);
}
