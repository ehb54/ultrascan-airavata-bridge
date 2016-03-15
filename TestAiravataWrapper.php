<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$launchAiravata = new AiravataWrapper();

$limsHost = "uslims3.uthscsa.edu";
$limsUser = "smarru";
$experimentName = "US3-Test";
$requestId = "uslims3_cauma3d_989";
$computeCluster = "alamo.uthscsa.edu";
//$computeCluster = "jureca.fz-juelich.de";
$queue = "batch";
$cores = 24;
$nodes = 8;
$mGroupCount = 8;
$wallTime = 2160;
$clusterUserName = null;
$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/test_airavata_wrapper/hpcinput-localhost-uslims3_CU_Boulder-01987.tar";
$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/test_airavata_wrapper";

$launchResult = $launchAiravata->launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                                            $computeCluster, $queue, $cores, $nodes, $mGroupCount,
                                                            $wallTime, $clusterUserName,
                                                            $inputFile, $outputDataDirectory);

var_dump($launchResult);

//if ($launchResult->$launchStatus) {
//    $experimentId = $launchResult->experimentId;
//} else {
//    $launchResult->message;
//}


