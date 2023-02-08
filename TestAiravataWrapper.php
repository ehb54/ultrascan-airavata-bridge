<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$airavataWrapper = new AiravataWrapper();

$limsUser = "metascheacc";
$experimentName = "US3-dev-ultrascan-test";
$requestId = "uslims3_cauma3d_989";


//PGA test
$limsHost = "pgadev.scigap.org";
$inputFile = "/var/www/portals/gateway-user-data/django-dev-ultrascan/test/hpcinput-localhost-uslims3_cauma3d-00950.tar";
$outputDataDirectory = "/var/www/portals/gateway-user-data/django-dev-ultrascan/test/";

$computeClusters = '[{"name":"expanse.sdsc.edu",
"queue":"compute",
"cores":2,
"nodes":1,
"mGroupCount":1,
"wallTime":30,
"estimatedMaxWallTime":100,
"clusterUserName":"us3",
"clusterScratch":"/expanse/lustre/scratch/us3/temp_project/airavata-workingdirs",
"clusterAllocationAccount":"uot111"
},{"name":"bridges2.psc.edu",
"queue":"compute",
"cores":2,
"nodes":1,
"mGroupCOunt":1,
"wallTime":30,
"estimatedMaxWallTime":100,
"clusterUserName":"us3",
"clusterScratch":"/expanse/lustre/scratch/us3/temp_project/airavata-workingdirs",
"clusterAllocationAccount":"uot111"
}]';

for ($x = 1; $x <=1; $x++) {

    $launchResult = $airavataWrapper->launch_autoscheduled_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
        $computeClusters, $inputFile, $outputDataDirectory,0);

    $expID = 0;
    if ( $launchResult[ 'launchStatus' ] ) {
        $expID = $launchResult[ 'experimentId' ];
        echo "Experiment created " . $expID . PHP_EOL;
    } else {
        echo "Experiment creation failed: " . $launchResult[ 'message' ]. PHP_EOL;
    }

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
