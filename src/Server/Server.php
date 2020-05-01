<?php

namespace App\Server;

class Server
{
    public function listen($server)
    {
        $method = $server['REQUEST_METHOD'];
        $uri    = $server['REQUEST_URI'];
        
        $param = explode('/', $uri);
        if (empty($param[0]))
            array_shift($param);

        $payload = self::getPayload($server);
        switch ($method) {
            case 'GET':
                if (count($param) > 1 && !empty($param[1]))
                    $method = self::camelize(strtolower('FIND_ONE'));
                else
                    $method = strtolower('FIND');

                break;
            case 'POST':
                if (!$payload) {
                    self::handlerPayloadError();
                    return;
                }
                
                $method = strtolower('CREATE');
                break;
            case 'PUT':
                if (!$payload) {
                    self::handlerPayloadError();
                    return;
                }
                
                $method = strtolower('UPDATE');
                break;
            case 'DELETE':
                $method = strtolower('REMOVE');
                break;
            default:
                http_response_code(500);
                echo json_encode(['error' => "Error. We can't handler this action!"]);
                break;
        }

        self::handlerController($uri, $method, $payload);
    }

    public function getPayload($server)
    {
        if (!isset($server['HTTP_CONTENT_LENGTH']) || !isset($server['CONTENT_LENGTH']) || !$server['HTTP_CONTENT_LENGTH'] || !$server['CONTENT_LENGTH'])
            return null;
        
        $content =  file_get_contents('php://input');
        $contentType = self::getContentType($server);

        //Convert urlencoded to JSON
        if ($contentType === 'application/x-www-form-urlencoded') {
            parse_str(urldecode($content), $result);
            return $result;
        }

        return \json_decode($content, true);
    }

    public function getContentType($server)
    {
        if (!isset($server['HTTP_CONTENT_TYPE']) || !isset($server['CONTENT_TYPE']) || !$server['HTTP_CONTENT_TYPE'] || !$server['CONTENT_TYPE'])
            return null;

        $contentType = $server['HTTP_CONTENT_TYPE'] ? $server['HTTP_CONTENT_TYPE'] : 
                       $server['CONTENT_TYPE'] ? $server['CONTENT_TYPE'] : '';

        return $contentType;
    }

    public function handlerPayloadError()
    {
        http_response_code(500);
        echo json_encode(['error' => 'Try removing the task instead of deleting its content.']);
    }

    public function handlerController($uri, $method, $payload)
    {
        if ($uri === '/') {
            http_response_code(200);
            echo json_encode([
                'hello' => 'Hi, if you need more info please let me know on rodrigopluz@hotmail.com',
                'tasks' => 'http://localhost:8080/task',
            ]);

            return;
        }

        $path = explode('/', $uri);
        if (empty($path[0]))
            array_shift($path);

        $className = ucfirst($path[0]);
        $param = isset($path[1]) ? $path[1] : null;

        $className = "App\Controllers\\" . $className . "Controller";

        if (!class_exists($className)) {
            http_response_code(500);
            echo json_encode(['error' => "Error. We can't handler this action!"]);
            return;
        }

        $return = call_user_func_array([new $className, $method], [$param, $payload]);
        echo json_encode($return);
    }

    public function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', lcfirst(ucwords($input, $separator)));
    }
}