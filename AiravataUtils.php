<?php

use Airavata\Model\Workspace\Project;
use Airavata\Model\Experiment\ExperimentModel;
use Airavata\Model\Experiment\UserConfigurationDataModel;
use Airavata\Model\Scheduling\ComputationalResourceSchedulingModel;
use Airavata\Model\Application\Io\InputDataObjectType;

function fetch_projectid($airavataclient, $authToken, $gatewayid, $user)
{
    if ($airavataclient->isUserExists($authToken, $gatewayid, $user)) {
        $userProjects = $airavataclient->getUserProjects($authToken, $gatewayid, $user, -1, 0);
        if ($userProjects == null || count($userProjects) == 0) {
            $projectId = create_project();
        } else {
            $projectId = $userProjects[0]->projectID;
        }
    } else {
        $projectId = create_project($airavataclient, $authToken, $gatewayid, $user);
    }

    return $projectId;
}

function create_project($airavataclient, $authToken, $gatewayid, $user)
{
    $project = new Project();
    $project->owner = $user;
    $project->name = "Default_Project";
    $project->description = "Default project";

    $projectId = $airavataclient->createProject($authToken, $gatewayid, $project);
    if ($projectId) {
        return $projectId;
    } else {
        echo 'Project cannot be created, please report to Support';
    }
}

function create_experiment_model($airavataclient, $authToken,
                                 $airavataconfig, $gatewayId, $projectId, $limsHost, $limsUser, $experimentName, $requestId,
                                 $computeCluster, $queue, $cores, $nodes, $mGroupCount, $wallTime, $clusterUserName, $inputFile, $outputDataDirectory)
{
    $applicationInterfaceId = null;
    if ($computeCluster != "jureca.fz-juelich.de") {
        $applicationInterfaceId = $airavataconfig['US3_APP'];
    } else {
        $applicationInterfaceId = $airavataconfig['US3_APP_JURECA'];
    }
    echo "app id is ", $applicationInterfaceId, PHP_EOL;

    $applicationInputs = $airavataclient->getApplicationInputs($authToken, $applicationInterfaceId);
    foreach ($applicationInputs as $applicationInput) {
        $applicationInputName = $applicationInput->name;
        switch ($applicationInputName) {
            case "Input_Tar_File":
                $inputFilePath = "file://scigap@$limsHost:" . $inputFile;
                $applicationInput->value = $inputFilePath;
                break;
            case "Wall_Time":
                $applicationInput->value = "-walltime=" . $wallTime;
                break;
            case "Parallel_Group_Count":
                $applicationInput->value = "-mgroupcount=" . $mGroupCount;
                break;
        }
    }

    $computeResourceId = null;
    if (($computeCluster == "alamo") || ($computeCluster == "alamo.uthscsa.edu")) {
        $computeResourceId = $airavataconfig['ALAMO_COMPUTE_ID'];
    } elseif (($computeCluster == "comet") || ($computeCluster == "comet.sdsc.edu") || ($computeCluster == "comet.sdsc.xsede.org")) {
        $computeResourceId = $airavataconfig['COMET_COMPUTE_ID'];
    } elseif (($computeCluster == "gordon") || ($computeCluster == "gordon.sdsc.edu") || ($computeCluster == "gordon.sdsc.xsede.org")) {
        $computeResourceId = $airavataconfig['GORDON_COMPUTE_ID'];
    } elseif (($computeCluster == "lonestar5") || ($computeCluster == "ls5.tacc.utexas.edu")) {
        $computeResourceId = $airavataconfig['LONESTAR5_COMPUTE_ID'];
    } elseif (($computeCluster == "stampede") || ($computeCluster == "stampede.tacc.utexas.edu") || ($computeCluster == "stampede.tacc.xsede.org")) {
        $computeResourceId = $airavataconfig['STAMPEDE_COMPUTE_ID'];
    } elseif (($computeCluster == "jureca") || ($computeCluster == "jureca.fz-juelich.de")) {
        $computeResourceId = $airavataconfig['JURECA_COMPUTE_ID'];
    }
    echo "compute host id is ", $computeResourceId, PHP_EOL;

    $storageResourceId = null;
    if ($limsHost == "uslims3.uthscsa.edu") {
        $storageResourceId = $airavataconfig['USLIMS3_UTHSCSA_STORAGE_ID'];
    } elseif ($limsHost == "uslims3.mbu.iisc.ernet.in") {
        $storageResourceId = $airavataconfig['USLIMS3_UTHSCSA_STORAGE_ID'];
    } elseif ($limsHost == "uslims3.latrobe.edu.au") {
        $storageResourceId = $airavataconfig['USLIMS3_UTHSCSA_STORAGE_ID'];
    } elseif ($limsHost == "uslims3.fz-juelich.de") {
        $storageResourceId = $airavataconfig['USLIMS3_UTHSCSA_STORAGE_ID'];
    }
    echo "storage host id is ", $storageResourceId, PHP_EOL;

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

    if (($computeCluster == "jureca") || ($computeCluster == "jureca.fz-juelich.de")) {
        $userConfigs->userDN = $clusterUserName;
    }

    $experimentModel = new ExperimentModel();
    $experimentModel->projectId = $projectId;
    $experimentModel->gatewayId = $gatewayId;
    $experimentModel->userName = $limsUser;
    $experimentModel->experimentName = $experimentName;
    $experimentModel->executionId = $applicationInterfaceId;
    $experimentModel->gatewayExecutionId = $requestId;
    $experimentModel->gatewayInstanceId = $limsHost;
    $experimentModel->userConfigurationData = $userConfigs;
    $experimentModel->experimentInputs = $applicationInputs;

    return $experimentModel;
}

?>