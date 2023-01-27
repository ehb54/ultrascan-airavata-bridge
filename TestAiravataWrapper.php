<?php

require_once "AiravataWrapper.php";

use SCIGAP\AiravataWrapper;

$airavataWrapper = new AiravataWrapper();

$limsUser = "metascheacc";
$experimentName = "US3-dev-ultrascan-test";
$requestId = "uslims3_cauma3d_989";

//$computeCluster = "ls5.tacc.utexas.edu";
//$queue = "normal";

//$computeCluster = "jureca.fz-juelich.de";
//$queue = "batch";

//$computeCluster = "comet.sdsc.xsede.org";
//$queue = "compute";

//
//$computeCluster = "stampede2.tacc.xsede.org";
//$queue = "normal";

//$computeCluster = "static-cluster.jetstream-cloud.org";
//$computeCluster = "cluster-testing";
//$queue = "batch";


$computeCluster = "expanse.sdsc.edu";
$queue = "compute";

//$cores = 24;
$cores = 2;
$nodes = 1;
$mGroupCount = 1;
//$wallTime = 120;
$wallTime = 30;
//$clusterUserName = "CN=swus1, O=Ultrascan Gateway, C=DE";
$clusterUserName = "us3";
$clusterScratch = "/expanse/lustre/scratch/us3/temp_project/airavata-workingdirs";
//$clusterUserName = "schneider3";
//$clusterScratch = "/p/scratch/cpaj1846/schneider3";
$clusterAllocationAccount = "uot111";


//$limsHost = "uslims3.aucsolutions.com";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/935290ca-c681-04e4-a910-7adf926b0b3b/hpcinput-localhost-uslims3_CU_Boulder-12544.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/935290ca-c681-04e4-a910-7adf926b0b3b/";

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

////JSC Host
//$limsHost = "uslims3.fz-juelich.de";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/31961e72-bbd6-73b4-1d4a-a6a5f8f5c415/hpcinput-localhost-uslims3_Aalto-01757.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/31961e72-bbd6-73b4-1d4a-a6a5f8f5c415/";

//$limsHost = "uslims.uleth.ca";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/bbaa4f2c-83dc-df74-19ad-9bfbe9dac01e/hpcinput-localhost-uslims3_test1-00027.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/test";

//Finnish LIMS
//$limsHost = "vm1584.kaj.pouta.csc.fi";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/e8565d44-cd90-2594-fd96-d026c2b05308/hpcinput-localhost-uslims3_Aalto-00378.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/e8565d44-cd90-2594-fd96-d026c2b05308";

//ULeth LIMS
//$limsHost = "uslims.uleth.ca";
//$inputFile = "/srv/www/htdocs/uslims3/uslims3_data/a280d69b-12b7-81c4-bd50-1a80417b9ec8/hpcinput-localhost-uslims3_CCH-11495.tar";
//$outputDataDirectory = "/srv/www/htdocs/uslims3/uslims3_data/a280d69b-12b7-81c4-bd50-1a80417b9ec8";

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
"extimatedMaxWallTime":100,
"clusterUserName":"us3",
"clusterScratch":"/expanse/lustre/scratch/us3/temp_project/airavata-workingdirs",
"clusterAllocationAccount":"uot111"
},{"name":"bridges2.psc.edu",
"queue":"compute",
"cores":2,
"nodes":1,
"mGroupCOunt":1,
"wallTime":30,
"extimatedMaxWallTime":100,
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
