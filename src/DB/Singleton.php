<?php

namespace App\DB;

class Singleton
{
    private static $instance;
    
    protected function __construct()
    {
    }
    
    protected function __clone()
    {
    }
    
    public function __wakeup()
    {
    }
    
    public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new static;
        
        return self::$instance;
    }
}