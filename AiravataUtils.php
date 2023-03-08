<?php
ini_set('memory_limit', '1024M');

use Airavata\Model\Data\Replica\DataProductModel;
use Airavata\Model\Data\Replica\DataProductType;
use Airavata\Model\Data\Replica\DataReplicaLocationModel;
use Airavata\Model\Data\Replica\ReplicaLocationCategory;
use Airavata\Model\Data\Replica\ReplicaPersistentType;
use Airavata\Model\Experiment\ExperimentModel;
use Airavata\Model\Experiment\ProjectSearchFields;
use Airavata\Model\Experiment\UserConfigurationDataModel;
use Airavata\Model\Scheduling\ComputationalResourceSchedulingModel;
use Airavata\Model\Security\AuthzToken;
use Airavata\Model\Workspace\Project;
use Airavata\Service\Profile\User\CPI\UserProfileServiceClient;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\TSocket;


function initialize_service_account_user_profile()
{

    $airavataconfig = parse_ini_file("airavata-client-properties.ini");

    $transport = new TSocket($airavataconfig['AIRAVATA_PROFILE_SERVICE_SERVER'], $airavataconfig['AIRAVATA_PROFILE_SERVICE_PORT']);
    try {
        $transport->setRecvTimeout($airavataconfig['AIRAVATA_TIMEOUT']);
        $transport->setSendTimeout($airavataconfig['AIRAVATA_TIMEOUT']);

        $protocol = new TBinaryProtocol($transport);
        $protocol = new TMultiplexedProtocol($protocol, "UserProfileService");
        $transport->open();

        $client = new UserProfileServiceClient($protocol);

        $authToken = new AuthzToken();
        $authToken->accessToken = get_service_account_access_token($airavataconfig);
        $authToken->claimsMap['gatewayID'] = $airavataconfig['GATEWAY_ID'];
        $authToken->claimsMap['userName'] = $airavataconfig['OIDC_USERNAME'];

        $client->initializeUserProfile($authToken);
    } catch (\Exception $e) {
        echo "Error trying to initialize service account user profile: " . $e->getMessage();
    }
    if ($transport->isOpen()) {
        $transport->close();
    }
}

function fetch_projectid($airavataclient, $authToken, $gatewayid, $user)
{
    // Make sure that user account is initialized in Airavata
    try {
        initialize_service_account_user_profile();

        // Look for a project that has the same name as the $user, or create it
        $filters = array(ProjectSearchFields::PROJECT_NAME => $user);
        $userProjects = $airavataclient->searchProjects($authToken,
            $gatewayid,
            $authToken->claimsMap['userName'],
            $filters,
            -1,  // limit
            0);  // offset
        if (count($userProjects) >= 1) {
            $projectId = $userProjects[0]->projectID;
        } else {
            $projectId = create_project($airavataclient, $authToken, $gatewayid, $user);
        }

        return $projectId;
    }catch (\Exception $e){
       var_dump($e->getTraceAsString());
}
}

function create_project($airavataclient, $authToken, $gatewayid, $user)
{
    $project = new Project();
    $project->owner = $authToken->claimsMap['userName'];
    $project->gatewayId = $gatewayid;
    // Name of project is the LIMS username
    $project->name = $user;
    $project->description = "Default project";

    $projectId = $airavataclient->createProject($authToken, $gatewayid, $project);
    if ($projectId) {
        return $projectId;
    } else {
        echo 'Project cannot be created, please report to Support';
    }
}


function select_storage_resource_id($airavataconfig,$limsHost) {
    $storageResourceId = null;
    switch ($limsHost) {
        case "demeler4.uleth.ca":
            $storageResourceId = $airavataconfig['USLIMS3_DEMELER4_STORAGE_ID'];
            break;
        case "uslims4.aucsolutions.com":
            $storageResourceId = $airavataconfig['USLIMS4_JS_STORAGE_ID'];
            break;
        case "demeler9.uleth.ca":
            $storageResourceId = $airavataconfig['USLIMS3_DEMELER9_STORAGE_ID'];
            break;
        case "uslimstest.genapp.rocks":
            $storageResourceId = $airavataconfig['USLIMS3_TESTGENAPP_STORAGE_ID'];
            break;
        case "uslimstest2.genapp.rocks":
            $storageResourceId = $airavataconfig['USLIMS3_TESTGENAPP2_STORAGE_ID'];
            break;
        case "uslims3.aucsolutions.com":
            $storageResourceId = $airavataconfig['USLIMS3_JS_STORAGE_ID'];
            break;
        case "uslims.uleth.ca":
            $storageResourceId = $airavataconfig['USLIMS3_ULETH_STORAGE_ID'];
            break;

        case "uslims.uleth.ca":
            $storageResourceId = $airavataconfig['USLIMS3_ULETH_STORAGE_ID'];
            break;
        case "alamo.uthscsa.edu":
            $storageResourceId = $airavataconfig['USLIMS3_ALAMO_STORAGE_ID'];
            break;

        case "vm1584.kaj.pouta.csc.fi":
            $storageResourceId = $airavataconfig['USLIMS3_CSC_FINLAND_STORAGE_ID'];
            break;
        case "uslims3.uthscsa.edu":
            $storageResourceId = $airavataconfig['USLIMS3_UTHSCSA_STORAGE_ID'];
            break;
        case "uslims3.iisc.ernet.in":
            $storageResourceId = $airavataconfig['USLIMS3_IISC_STORAGE_ID'];
            break;
        case "uslims3.mbu.iisc.ernet.in":
            $storageResourceId = $airavataconfig['USLIMS3_IISC_STORAGE_ID'];
            break;
        case "uslims3.latrobe.edu.au":
            $storageResourceId = $airavataconfig['USLIMS3_LATROBE_STORAGE_ID'];
            break;
        case "uslims3.fz-juelich.de":
            $storageResourceId = $airavataconfig['USLIMS3_JUELICH_STORAGE_ID'];
            break;
        case "gf4.ucs.indiana.edu":
            $storageResourceId = $airavataconfig['USLIMS3_GF4_STORAGE_ID'];
            break;
        case "uslims.aucsolutions.com":
            $storageResourceId = $airavataconfig['USLIMS_AUCSOLUTIONS_STORAGE_ID'];
            break;
        case "uslims.uleth.ca":
            $storageResourceId = $airavataconfig['USLIMS_ULETH_STORAGE_ID'];
            break;
        case "uslims.latrobe.edu.au":
            $storageResourceId = $airavataconfig['USLIMS_LATROBE_STORAGE_ID'];
            break;
        case "uslims.fz-juelich.de":
            $storageResourceId = $airavataconfig['USLIMS_JUELICH_STORAGE_ID'];
            break;
        case "pgadev.scigap.org":
            $storageResourceId = $airavataconfig['USLIMS_TESTING_STORAGE_ID'];
            break;
    }
    return $storageResourceId;
}


function select_compute_resource_id($airavataconfig,$computeCluster){
    $computeResourceId = null;
    switch ($computeCluster) {
        case "bridges2.psc.edu":
            $computeResourceId = $airavataconfig['BRIDGES2_COMPUTE_ID'];
            break;
        case "expanse.sdsc.edu":
            $computeResourceId = $airavataconfig['EXPANSE_COMPUTE_ID'];
            break;
        case "comet.sdsc.xsede.org":
            $computeResourceId = $airavataconfig['COMET_COMPUTE_ID'];
            break;
        case "ls5.tacc.utexas.edu":
            $computeResourceId = $airavataconfig['LONESTAR5_COMPUTE_ID'];
            break;
        case "ls6.tacc.utexas.edu":
            $computeResourceId = $airavataconfig['LONESTAR6_COMPUTE_ID'];
            break;
        case "stampede2.tacc.xsede.org":
            $computeResourceId = $airavataconfig['STAMPEDE2_COMPUTE_ID'];
            break;
        case "jureca.fz-juelich.de":
            $computeResourceId = $airavataconfig['JURECA_COMPUTE_ID'];
            break;
        case "juwels.fz-juelich.de":
            $computeResourceId = $airavataconfig['JUWELS_COMPUTE_ID'];
            break;
        case "static-cluster.jetstream-cloud.org":
            $computeResourceId = $airavataconfig['JETSTREAM_COMPUTE_ID'];
            break;
        case "anvil.rcac.purdue.edu":
            $computeResourceId = $airavataconfig['ANVIL_COMPUTE_ID'];
            break;
    }
    return $computeResourceId;
}

function create_experiment_model($airavataclient, $authToken,
                                 $airavataconfig, $gatewayId, $projectId, $limsHost, $limsUser, $experimentName, $requestId,
                                 $computeCluster, $queue, $cores, $nodes, $mGroupCount, $wallTime, $clusterUserName,
                                 $clusterScratch, $clusterAllocationAccount, $inputFile, $outputDataDirectory,
                                 $memoryreq, $autoScheduled)
{
    $storageResourceId = select_storage_resource_id($airavataconfig,$limsHost);


    $applicationInterfaceId = $airavataconfig['US3_APP'];

    $applicationInputs = $airavataclient->getApplicationInputs($authToken, $applicationInterfaceId);
    foreach ($applicationInputs as $applicationInput) {
        $applicationInputName = $applicationInput->name;
        switch ($applicationInputName) {
            case "Input_Tar_File":
                $dataProductModel = new DataProductModel();
                $dataProductModel->gatewayId = $gatewayId;
                $dataProductModel->ownerName = $authToken->claimsMap['userName'];
                $dataProductModel->productName = basename($inputFile);
                $dataProductModel->dataProductType = DataProductType::FILE;

                $dataReplicationModel = new DataReplicaLocationModel();
                $dataReplicationModel->storageResourceId = $storageResourceId;
                $dataReplicationModel->replicaName = basename($inputFile) . " gateway data store copy";
                $dataReplicationModel->replicaLocationCategory = ReplicaLocationCategory::GATEWAY_DATA_STORE;
                $dataReplicationModel->replicaPersistentType = ReplicaPersistentType::TRANSIENT;
                $dataReplicationModel->filePath = "file://" . $limsHost . ":" . $inputFile;

                $dataProductModel->replicaLocations[] = $dataReplicationModel;
                $replicaURI = $airavataclient->registerDataProduct($authToken, $dataProductModel);

                $applicationInput->value = $replicaURI;
                break;
            case "Wall_Time":
                $applicationInput->value = "-walltime=" . $wallTime;
                break;
            case "Parallel_Group_Count":
                $applicationInput->value = "-mgroupcount=" . $mGroupCount;
                break;
        }
    }

    $computeResourceId = select_compute_resource_id($airavataconfig,$computeCluster);

    $scheduling = new ComputationalResourceSchedulingModel();
    $scheduling->resourceHostId = $computeResourceId;
    $scheduling->totalCPUCount = $cores;
    $scheduling->nodeCount = $nodes;
    $scheduling->queueName = $queue;
    $scheduling->wallTimeLimit = $wallTime;

    $userConfigs = new UserConfigurationDataModel();
    $userConfigs->computationalResourceScheduling = $scheduling;
    $userConfigs->storageId = $storageResourceId;
    $userConfigs->experimentDataDir = $outputDataDirectory;
    $userConfigs->airavataAutoSchedule = $autoScheduled;

    if (($computeCluster == "jureca") || ($computeCluster == "jureca.fz-juelich.de") || ($computeCluster == "juwels") || ($computeCluster == "juwels.fz-juelich.de")) {

        $scheduling->overrideLoginUserName = $clusterUserName;
        $scheduling->overrideScratchLocation = $clusterScratch;
        $scheduling->overrideAllocationProjectNumber = $clusterAllocationAccount;

    }
    if ($memoryreq > 0) {
        $scheduling->totalPhysicalMemory = $memoryreq;
    }

    $experimentModel = new ExperimentModel();
    $experimentModel->projectId = $projectId;
    $experimentModel->gatewayId = $gatewayId;
    $experimentModel->userName = $authToken->claimsMap['userName'];
    $experimentModel->experimentName = $experimentName;
    $experimentModel->executionId = $applicationInterfaceId;
    $experimentModel->gatewayExecutionId = $requestId;
    $experimentModel->gatewayInstanceId = $limsHost;
    $experimentModel->userConfigurationData = $userConfigs;
    $experimentModel->experimentInputs = $applicationInputs;
    $experimentModel->experimentOutputs = $airavataclient->getApplicationOutputs($authToken, $applicationInterfaceId);

    return $experimentModel;
}




function create_experiment_model_with_auto_scheduling($airavataclient, $authToken,
                                 $airavataconfig, $gatewayId, $projectId, $limsHost, $limsUser, $experimentName, $requestId,
                                 $computeClusters, $inputFile, $outputDataDirectory)
{
    //TODO : replace once backend is done
    $comCRs =  json_decode($computeClusters);
    $storageResourceId = select_storage_resource_id($airavataconfig,$limsHost);

    $applicationInterfaceId = $airavataconfig['US3_APP'];

    $applicationInputs = $airavataclient->getApplicationInputs($authToken, $applicationInterfaceId);
    foreach ($applicationInputs as $applicationInput) {
        $applicationInputName = $applicationInput->name;
        switch ($applicationInputName) {
            case "Input_Tar_File":
                $dataProductModel = new DataProductModel();
                $dataProductModel->gatewayId = $gatewayId;
                $dataProductModel->ownerName = $authToken->claimsMap['userName'];
                $dataProductModel->productName = basename($inputFile);
                $dataProductModel->dataProductType = DataProductType::FILE;

                $dataReplicationModel = new DataReplicaLocationModel();
                $dataReplicationModel->storageResourceId = $storageResourceId;
                $dataReplicationModel->replicaName = basename($inputFile) . " gateway data store copy";
                $dataReplicationModel->replicaLocationCategory = ReplicaLocationCategory::GATEWAY_DATA_STORE;
                $dataReplicationModel->replicaPersistentType = ReplicaPersistentType::TRANSIENT;
                $dataReplicationModel->filePath = "file://" . $limsHost . ":" . $inputFile;

                $dataProductModel->replicaLocations[] = $dataReplicationModel;
                $replicaURI = $airavataclient->registerDataProduct($authToken, $dataProductModel);

                $applicationInput->value = $replicaURI;
                break;
            case "Wall_Time":
                $applicationInput->value = "-walltime=0";
                break;
            case "Parallel_Group_Count":
                $applicationInput->value = "-mgroupcount=0";
                break;
        }
    }

    $schedulingObjects = array();


   foreach ($comCRs as $comCR) {
       $computeResourceId = select_compute_resource_id($airavataconfig,$comCR->name);
       $scheduling = new ComputationalResourceSchedulingModel();
       $scheduling->resourceHostId = $computeResourceId;
       $scheduling->totalCPUCount = $comCR->cores;
       $scheduling->nodeCount = $comCR->nodes;
       $scheduling->queueName = $comCR->queue;
       $scheduling->wallTimeLimit = $comCR->wallTime;
       $scheduling->totalPhysicalMemory = $comCR->memreq;
       $scheduling->mGroupCount = $comCR->mGroupCount;

       if (($comCR->name == "jureca") || ($comCR->name == "jureca.fz-juelich.de") || ($comCR->name == "juwels") || ($comCR->name == "juwels.fz-juelich.de")) {

           $scheduling->overrideLoginUserName = $comCR->clusterUserName;
           $scheduling->overrideScratchLocation = $comCR->clusterScratch;
           $scheduling->overrideAllocationProjectNumber = $comCR->clusterAllocationAccount;;
       }


       array_push($schedulingObjects,$scheduling);
   }



    $userConfigs = new UserConfigurationDataModel();
    $userConfigs->storageId = $storageResourceId;
    $userConfigs->experimentDataDir = $outputDataDirectory;
    $userConfigs->airavataAutoSchedule = true;
    $userConfigs->autoScheduledCompResourceSchedulingList=$schedulingObjects;


    $experimentModel = new ExperimentModel();
    $experimentModel->projectId = $projectId;
    $experimentModel->gatewayId = $gatewayId;
    $experimentModel->userName = $authToken->claimsMap['userName'];
    $experimentModel->experimentName = $experimentName;
    $experimentModel->executionId = $applicationInterfaceId;
    $experimentModel->gatewayExecutionId = $requestId;
    $experimentModel->gatewayInstanceId = $limsHost;
    $experimentModel->userConfigurationData = $userConfigs;
    $experimentModel->experimentInputs = $applicationInputs;
    $experimentModel->experimentOutputs = $airavataclient->getApplicationOutputs($authToken, $applicationInterfaceId);

    return $experimentModel;

}



function get_service_account_access_token($airavataconfig)
{
    // fetch access token for service account, equivalent of following:
    // curl -u $OIDC_CLIENT_ID:$OIDC_CLIENT_SECRET -d grant_type=client_credentials $OIDC_TOKEN_URL
    $r = curl_init($airavataconfig['OIDC_TOKEN_URL']);
    curl_setopt ($r, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($r, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($r, CURLOPT_ENCODING, 1);
    curl_setopt($r, CURLOPT_SSL_VERIFYPEER, 0);
    if (array_key_exists("OIDC_CAFILE_PATH", $airavataconfig)) {
        $filepath = realpath(dirname(__FILE__));
        curl_setopt($r, CURLOPT_CAINFO, $filepath . "/" . $airavataconfig['OIDC_CAFILE_PATH']);
    }
    curl_setopt($r, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . base64_encode($airavataconfig['OIDC_CLIENT_ID'] . ":" . $airavataconfig['OIDC_CLIENT_SECRET']),
    ));
    // Assemble POST parameters for the request.
    $post_fields = "grant_type=client_credentials";

    // Obtain and return the access token from the response.
    curl_setopt($r, CURLOPT_POST, true);
    curl_setopt($r, CURLOPT_POSTFIELDS, $post_fields);

    $response = curl_exec($r);
    if ($response === FALSE) {
        throw new Exception("Failed to retrieve API Access Token: curl_exec() failed. Error: " . curl_error($r));
    }

    $result = json_decode($response);
    return $result->access_token;
}

?>
