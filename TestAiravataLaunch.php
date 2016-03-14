<?php
ini_set('memory_limit', '-1');

include "TestAiravataLaunch.php";

$launchAiravata = new \SCIGAP\LaunchAiravata();

$launchResult = $launchAiravata->launch_airavata_experiment("test","test","test","test","test","test",10,10,10,10,"test","test");

var_dump($array);

