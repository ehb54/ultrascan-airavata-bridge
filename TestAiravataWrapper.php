<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$airavataWrapper = new AiravataWrapper();

$limsUser = "smarru";
$experimentName = "US3-Staging-Test";
$requestId = "uslims3_cauma3d_989";

//$computeCluster = "ls5.tacc.utexas.edu";
//$queue = "normal";

//$computeCluster = "jureca.fz-juelich.de";
//$queue = "batch";

//$computeCluster = "alamo.uthscsa.edu";
//$queue = "batch";

$computeCluster = "comet.sdsc.xsede.org";
$queue = "compute";
//

//
//$computeCluster = "stampede.tacc.xsede.org";
//$queue = "normal";

//
//$computeCluster = "stampede2.tacc.xsede.org";
//$queue = "normal";

//$computeCluster = "static-cluster.jetstream-cloud.org";
//$queue = "batch";

//$computeCluster = "juwels.fz-juelich.de";
//$queue = "batch";

$cores = 24;
$nodes = 1;
$mGroupCount = 1;
$wallTime = 120;
//$clusterUserName = "CN=swus1, O=Ultrascan Gateway, C=DE";
//$clusterUserName = "sureshmarru1";
//$clusterScratch = "/p/scratch/cpaj1846/sureshmarru1";
$clusterUserName = "schneider3";
$clusterScratch = "/p/scratch/cpaj1846/schneider3";
$clusterAllocationAccount = "hkn00";

//AU
//$limsHost = "uslims3.latrobe.edu.au";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/a1f91936-5bc6-76b4-b96c-89961a5944e8/hpcinput-uslims3.latrobe.edu.au-uslims3_latrobe-00810.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/test";

//iisc
//$limsHost = "uslims3.mbu.iisc.ernet.in";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/72e8b914-759a-c934-cd50-b13031365bce/hpcinput-localhost-uslims3_ICTMumbai-00057.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/test";

////Alamo
//$limsHost = "alamo.uthscsa.edu";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/f3c40bdc-324f-b0f4-0d62-20da04862293/hpcinput-localhost-uslims3_Workshop-00619.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/f3c40bdc-324f-b0f4-0d62-20da04862293";

////JS Host
//$limsHost = "uslims3.aucsolutions.com";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/dbecb29c-a668-3914-e1d4-25865dcb9718/hpcinput-localhost-uslims3_cauma3d-01583.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/dbecb29c-a668-3914-e1d4-25865dcb9718";

$limsHost = "demeler6.uleth.ca";
$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/bbaa4f2c-83dc-df74-19ad-9bfbe9dac01e/hpcinput-localhost-uslims3_test1-00027.tar";
$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/test";

//gw143 - iu lims
//$limsHost = "gw143.iu.xsede.org";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/223c092a-b633-6b14-198e-43f6acb02c53/hpcinput-localhost-uslims3_cauma3d-00973.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/14dc85ff-320b-d284-299b-6f7cf245b7a5";

//PGA test
//$limsHost = "gf4.ucs.indiana.edu";
//$inputFile = "/var/www/portals/gateway-user-data/ultrascan/test/hpcinput-localhost-uslims3_cauma3d-00950.tar";
//$outputDataDirectory = "/var/www/portals/gateway-user-data/ultrascan/test";

for ($x = 1; $x <=1; $x++) {

    $launchResult = $airavataWrapper->launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                                            $computeCluster, $queue, $cores, $nodes, $mGroupCount,
                                                            $wallTime, $clusterUserName, $clusterScratch, $clusterAllocationAccount,
                                                            $inputFile, $outputDataDirectory);

$expID = 0;
if ( $launchResult[ 'launchStatus' ] ) {
    $expID = $launchResult[ 'experimentId' ];
    echo "Experiment created " . $expID . PHP_EOL;
} else {
    echo "Experiment creation failed: " . $launchResult[ 'message' ]. PHP_EOL;
}

//var_dump($launchResult);

}

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
//
//$expID = "US3-Staging-Test_2ee6891b-1123-4cdf-ae88-3f3869af590c";
////
//while (true) {
//    $experimentState = $airavataWrapper->get_experiment_status($expID);
//    var_dump($experimentState);
//}