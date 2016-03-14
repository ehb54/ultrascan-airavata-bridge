<?php

use Airavata\Model\Workspace\Project;
use Airavata\Model\Experiment;

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

function create_experiment_object($projectId,$limsHost, $limsUser, $experimentName, $requestId)
{

    $experiment = new Experiment();
    $experiment->projectID = $projectId;
    $experiment->userName = $limsUser;
    $experiment->gatewayInstanceId = $limsHost;
    $experiment->gatewayExecutionId = $requestId;

    $experiment->name = $experimentName;
    $experiment->applicationId = "";
    $experiment->userConfigurationData = "";
    $experiment->experimentInputs = "";

    return $experiment;
}


?>