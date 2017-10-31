<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$airavataWrapper = new AiravataWrapper();

$limsUser = "smarru";
$experimentName = "US3-Test";
$requestId = "uslims3_cauma3d_989";

//$computeCluster = "ls5.tacc.utexas.edu";
//$queue = "normal";

//$computeCluster = "jureca.fz-juelich.de";
//$queue = "batch";

//$computeCluster = "alamo.uthscsa.edu";
//$queue = "batch";

//$computeCluster = "comet.sdsc.xsede.org";
//$queue = "compute";
//

//
//$computeCluster = "stampede.tacc.xsede.org";
//$queue = "normal";

$computeCluster = "static-cluster.jetstream-cloud.org";
$queue = "batch";


$cores = 16;
$nodes = 1;
$mGroupCount = 1;
$wallTime = 120;
//$clusterUserName = "CN=swus1, O=Ultrascan Gateway, C=DE";
$clusterUserName = "swus1";
//$clusterScratch = "";
$clusterScratch = "/work/ultrascn/swus1";

//uslims3
$limsHost = "uslims3.uthscsa.edu";
$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/164b976a-1ce5-c714-417a-d599f8f759fc/hpcinput-localhost-uslims3_NVAX-05268.tar";
$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/14dc85ff-320b-d284-299b-6f7cf245b7a5";

//gw143 - iu lims
//$limsHost = "gw143.iu.xsede.org";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/223c092a-b633-6b14-198e-43f6acb02c53/hpcinput-localhost-uslims3_cauma3d-00973.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/14dc85ff-320b-d284-299b-6f7cf245b7a5";

//PGA test
//$limsHost = "gf4.ucs.indiana.edu";
//$inputFile = "/var/www/portals/gateway-user-data/ultrascan/test/hpcinput-localhost-uslims3_cauma3d-00950.tar";
//$outputDataDirectory = "/var/www/portals/gateway-user-data/ultrascan/test";

//for ($x = 0; $x <= 0; $x++) {

    $launchResult = $airavataWrapper->launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                                            $computeCluster, $queue, $cores, $nodes, $mGroupCount,
                                                            $wallTime, $clusterUserName, $clusterScratch,
                                                            $inputFile, $outputDataDirectory);

////var_dump($launchResult);

$expID = 0;
if ( $launchResult[ 'launchStatus' ] ) {
    $expID = $launchResult[ 'experimentId' ];
    echo "Experiment created " . $expID . PHP_EOL;
} else {
    echo "Experiment creation failed: " . $launchResult[ 'message' ]. PHP_EOL;
}

//}

//$cancelResult = $airavataWrapper->terminate_airavata_experiment("US3-Test_c0f79811-a55b-4fd4-a2d5-f5db268ff871");
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

//$expID = "US3-Test_99544916-6357-4810-ba2d-cd1885e5d5fa";
//
//while (true) {
//    $experimentState = $airavataWrapper->get_experiment_status($expID);
//    var_dump($experimentState);
//}