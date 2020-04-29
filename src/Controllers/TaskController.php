<?php

namespace App\Controllers;

use App\Models\Task as Model;

class TaskController
{
    public function find($param)
    {
        $tasks = Model::find();

        if (!$tasks) {
            http_response_code(200);
            return json_encode(["message" => "Wow. You have nothing else to do. Enjoy the rest of your day!"]);
        }

        http_response_code(200);
        return json_encode($tasks);
    }

    public function findOne($param)
    {
        $task = Model::find();
        if (!$task) {
            http_response_code(200);
            return json_encode(["message" => "Wow. You have nothing else to do. Enjoy the rest of your day!"]);
        }

        http_response_code(200);
        return json_encode($task);
    }

    public function create($param, $payload)
    {
        $task = new Model($payload['type'], $payload['done'], $payload['content'], $payload['sortOrder']);

        if (empty($task->getUUID())) {
            echo('<pre>');
            var_dump($task);
            exit;

            return ['statusCode' => 500, 'data' => $task->getErrorMessage()];
        }

        $task->save($task);
        return ['statusCode' => 200, 'data' => json_encode($task)];
    }

    public function update($param, $payload)
    {
        echo "no method update";
        echo "<pre>";
        var_dump($param);
        var_dump($payload);
        
        return ['statusCode' => 200, 'data' => 'Success'];
    }

    public function remove($param)
    {
        echo "no method remove";
        echo "<pre>";
        var_dump($param);

        return ['statusCode' => 200, 'data' => 'Success'];
    }
}