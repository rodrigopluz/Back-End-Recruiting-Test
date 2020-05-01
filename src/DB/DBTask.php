<?php

namespace App\DB;

use App\DB\Single;

class DBTask extends Single
{
    const COLLECTION_TASK = 'tasks.json';
    const DB_FILE = __DIR__ . '/Collections/';

    private $collection;

    protected function __construct()
    {
        $this->collection = [];
    }

    public function add($domain)
    {
        array_push($this->collection, $domain);
        $this->store(self::COLLECTION_TASK, $this->collection);
        return $domain;
    }

    public function update($uuid, $domain)
    {
        $idx = array_search($uuid, array_column(get_object_vars($this->collection), 'uuid'));
        if ($idx) {
            $this->collection[$idx] = $domain;
            $this->store(self::COLLECTION_TASK, $this->collection);
            return true;
        }

        return false;
    }

    public function delete($uuid)
    {
        $idx = array_search($uuid, array_column(get_object_vars($this->collection), 'uuid'));
        if ($idx) {
            array_splice($this->collection, $idx, 1);
            $this->store(self::COLLECTION_TASK, $this->collection);
            return true;
        }

        return false;
    }

    public function findAll()
    {
        $file = $this->read(self::COLLECTION_TASK);
        return $file;
    }

    public function findById($uuid)
    {
        $idx = array_search($uuid, array_column($this->collection, 'uuid'));
        if ($idx)
            return $this->collection[$idx];
        
        return null;
    }

    public function store($fileName, $content)
    {
        echo('<pre>');
        var_dump($content);
        exit;

        $file = fopen(self::DB_FILE . self::COLLECTION_TASK, 'w');
        fwrite($file, $content);
        fclose($file);
    }

    public function read($fileName)
    {
        $content = file_get_contents(self::DB_FILE . $fileName);
        if (empty($content))
            return null;
        
        return json_encode($content);
    }
}