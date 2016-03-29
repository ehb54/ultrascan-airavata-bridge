<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$airavataWrapper = new AiravataWrapper();

$limsHost = "uslims3.uthscsa.edu";
#$limsHost = "gw54.iu.xsede.org";
$limsUser = "smarru";
$experimentName = "US3-Test";
$requestId = "uslims3_cauma3d_989";

//$computeCluster = "ls5.tacc.utexas.edu";
//$queue = "normal";

$computeCluster = "jureca.fz-juelich.de";
$queue = "batch";

//$computeCluster = "alamo.uthscsa.edu";
//$queue = "batch";

//$computeCluster = "comet.sdsc.xsede.org";
//$queue = "compute";
//
//$computeCluster = "gordon.sdsc.xsede.org";
//$queue = "normal";
//
//$computeCluster = "stampede.tacc.xsede.org";
//$queue = "normal";

$cores = 16;
$nodes = 1;
$mGroupCount = 1;
$wallTime = 120;
$clusterUserName = "CN=swus1, O=Ultrascan Gateway, C=DE";
$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/223c092a-b633-6b14-198e-43f6acb02c53/hpcinput-localhost-uslims3_cauma3-04718.tar";
#$inputFile = "/home/pga/portals/gateway-user-data/ultrascan-testing/smarru/691e9a10a99350f7ca2379ad3733955a/hpcinput-localhost-uslims3_CU_Boulder-01828.tar";
$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/223c092a-b633-6b14-198e-43f6acb02c53";

//for ($x = 0; $x <= 0; $x++) {

    $launchResult = $airavataWrapper->launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                                            $computeCluster, $queue, $cores, $nodes, $mGroupCount,
                                                            $wallTime, $clusterUserName,
                                                            $inputFile, $outputDataDirectory);

//var_dump($launchResult);

$expID = 0;
if ( $launchResult[ 'launchStatus' ] ) {
    $expID = $launchResult[ 'experimentId' ];
    echo "Experiment created" . $expID . PHP_EOL;
} else {
    echo "Experiment creation failed: " . $launchResult[ 'message' ]. PHP_EOL;
}

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

//while (true) {
//    $experimentState = $airavataWrapper->get_experiment_status($expID);
//    var_dump($experimentState);
//}

echo gethostname();