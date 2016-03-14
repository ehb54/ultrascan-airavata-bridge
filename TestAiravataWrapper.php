<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$launchAiravata = new AiravataWrapper();

$launchResult = $launchAiravata->launch_airavata_experiment("test","test","test","test","test","test","test",10,10,10,10,"test","test");

var_dump($launchResult);

