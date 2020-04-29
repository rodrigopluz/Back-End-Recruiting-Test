<?php

namespace App\Models;

use App\DB\DBTask;

class Task
{
    public $uuid = "";
    public $type = "";
    public $content = "";
    public $sortOrder = 0;
    public $done = false;
    public $dateCreated = "";
    public $errorMessage = "";
    public $typesAllowed = ["shopping", "work"];

    public function __construct($type, $done, $content, $sortOrder)
    {
        if (!$this->validateType($type) && !$this->validateContent($content))
            $this->uuid = "";
        else {
            $this->uuid = md5(uniqid(rand(), true));
            $this->done = $done;
            $this->type = $type;
            $this->content = $content;
            $this->sortOrder = $sortOrder;
            $this->dateCreated = new \DateTime();
        }
    }

    public function validateType($type)
    {
        if (!\in_array($type, $this->typesAllowed)) {
            $this->setErrorMessage("The task type you provided is not supported. You can only use shopping or work.");
            return null;
        }
    }

    public function validateContent($content)
    {
        if (!isset($content) || empty($content)) {
            $this->setErrorMessage("Bad move! Try removing the task instead of deleting its content.");
            return null;
        }
    }

    public function setAsDone($uuid)
    {
        $this->done = true;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getContent()
    {
        return $this->content;
    }

    public function setSortOrder($order)
    {
        $this->sortOrder = $order;
    }
    
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    public function getTask()
    {
        return $this;
    }

    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    // reflector mehods to dbTask
    public function save($domain)
    {
        $db = DBTask::getInstance();
        $saved = $db->add($domain);
        return $saved;
    }

    public function update($uuid, $domain)
    {
        if (!isset(self::$store))
            self::$store = DBTask::getInstance();

        return self::$store->update($uuid, $domain);
    }

    public function remove($uuid)
    {
        if (!isset(self::$store))
            self::$store = DBTask::getInstance();
        
        return self::$store->remove($uuid);
    }

    public function find()
    {
        return DBTask::getInstance()->findAll();
    }

    public function findOne($uuid)
    {
        if (!isset(self::$store))
            self::$store = DBTask::getInstance();
        
        return self::$store->findOne($uuid);
    }
}