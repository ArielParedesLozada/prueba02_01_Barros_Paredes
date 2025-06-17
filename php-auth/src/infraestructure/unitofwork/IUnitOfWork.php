<?php

namespace app\infraestructure\unitofwork;

interface IUnitOfWork
{
    public function users();

    public function commit();

    public function rollback();

    public function beginTransaction();
}
