<?php

require(__DIR__ . "/vendor/autoload.php");

use App\Server\Server;

Server::listen($_SERVER);