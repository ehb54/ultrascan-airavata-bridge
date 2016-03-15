<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$airavataWrapper = new AiravataWrapper();

$limsHost = "uslims3.uthscsa.edu";
$limsUser = "smarru";
$experimentName = "US3-Test";
$requestId = "uslims3_cauma3d_989";

//$computeCluster = "ls5.tacc.utexas.edu";
//$queue = "normal";

//$computeCluster = "alamo.uthscsa.edu";
//$queue = "batch";

//$computeCluster = "comet.sdsc.xsede.org";
//$queue = "compute";
//
//$computeCluster = "gordon.sdsc.xsede.org";
//$queue = "normal";
//
$computeCluster = "stampede.tacc.xsede.org";
$queue = "normal";

$cores = 16;
$nodes = 1;
$mGroupCount = 1;
$wallTime = 120;
$clusterUserName = null;
$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/test_airavata_wrapper/hpcinput-localhost-uslims3_CAUMA-19310.tar";
$outputDataDirectory = "test_airavata_wrapper";

//$launchResult = $airavataWrapper->launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
//                                                            $computeCluster, $queue, $cores, $nodes, $mGroupCount,
//                                                            $wallTime, $clusterUserName,
//                                                            $inputFile, $outputDataDirectory);
//
//var_dump($launchResult);
//
//if ( $launchResult[ 'launchStatus' ] ) {
//    $expID = $launchResult[ 'experimentId' ];
//    echo "Experiment created" . $expID . PHP_EOL;
//} else {
//    echo "Experiment creation failed: " . $launchResult[ 'message' ]. PHP_EOL;
//}

//$cancelResult = $airavataWrapper->terminate_airavata_experiment("US3-Test_7f7b4e10-a32e-4fb8-a1b2-2cfa6632c194");
//
//if ($cancelResult['terminateStatus']) {
//    echo "Experiment successfully cancelled";
//    return true;
//} else {
//    echo "Experiment Termination Failed: " . $cancelResult['message'] . PHP_EOL;
//    return false;
//}

//$experimentError = $airavataWrapper->get_experiment_errors("US3-Test_8a392389-c3d7-46aa-a08d-ae2a982ebd82");
//var_dump($experimentError);

//$experimentState = $airavataWrapper->get_experiment_status("US3-Test_d637a9b3-e526-4390-bd06-936685f3f593");
//var_dump($experimentState);

echo gethostname();