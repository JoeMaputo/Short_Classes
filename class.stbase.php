<?php

/*
* @autor Martynuk Andrew <j-map@mail.ru>
*/


use PDO;

class Base
{
    private $host = 'localhost';
    private $base = 'имя базы данных';
    private $user = 'пользователь ДБ';
    private $pass = 'пароль';
    private static $pdo;
    
    public function prepare($q)
    {
        if(!(self::$pdo instanceof PDO))
        {
            $options = [PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
            self::$pdo = new PDO("mysql:host=$this->host;dbname=$this->base;charset=UTF8", $this->user, $this->pass, $options);
        }
        return self::$pdo->prepare($q);
    }
    
    public function getresult($stm, $arr)
    {
        if($stm instanceof \PDOStatement){
            $stm->execute($arr);
            return $stm->fetchAll();
        } else{
            return FALSE;
        }
    }
    
    public function sel($q, $arr = [])
    {
        $stm = $this->prepare($q);
        return $this->getresult($stm, $arr);
    }

    public function ins($q, $arr)
    {
        $stm = $this->prepare($q);
        $stm->execute($arr);
        return self::$pdo->lastInsertId();
    }

    public function upd($q, $arr)
    {
        $stm = $this->prepare($q);
        $stm->execute($arr);
    }
}


