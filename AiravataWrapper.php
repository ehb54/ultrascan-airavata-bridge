<?php

namespace SCIGAP;

$GLOBALS['THRIFT_ROOT'] = 'lib/Thrift/';
$GLOBALS['AIRAVATA_ROOT'] = 'lib/Airavata/';

require_once $GLOBALS['THRIFT_ROOT'] . 'Transport/TTransport.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Transport/TSocket.php';
use Thrift\Transport\TSocket;
require_once $GLOBALS['THRIFT_ROOT'] . 'Protocol/TProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Protocol/TBinaryProtocol.php';
use Thrift\Protocol\TBinaryProtocol;
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TException.php';
use Thrift\Exception\TException;
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TApplicationException.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TProtocolException.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Exception/TTransportException.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Base/TBase.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Type/TType.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Type/TMessageType.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Factory/TStringFuncFactory.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'StringFunc/TStringFunc.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'StringFunc/Core.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'Type/TConstant.php';

require_once $GLOBALS['AIRAVATA_ROOT'] . 'API/Airavata.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'API/Types.php';
use Airavata\API\AiravataClient;

require_once $GLOBALS['AIRAVATA_ROOT'] . 'API/Error/Types.php';
use Airavata\API\Error\InvalidRequestException;
use Airavata\API\Error\AiravataClientException;
use Airavata\API\Error\AiravataSystemException;
use Airavata\API\Error\ExperimentNotFoundException;

require_once $GLOBALS['AIRAVATA_ROOT'] . 'Model/Security/Types.php';
use Airavata\Model\Security\AuthzToken;

require_once $GLOBALS['AIRAVATA_ROOT'] . 'Model/Workspace/Types.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'Model/Experiment/Types.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'Model/AppCatalog/AppInterface/Types.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'Model/AppCatalog/AppDeployment/Types.php';
require_once $GLOBALS['AIRAVATA_ROOT'] . 'Model/AppCatalog/ComputeResource/Types.php';

require_once "AiravataWrapperInterface.php";
require_once "AiravataUtils.php";


class AiravataWrapper implements AiravataWrapperInterface
{
    private $airavataclient = AiravataClient;
    private $transport = TSocket;
    private $authToken = AuthzToken;
    private $airavataconfig;
    private $gatewayId;

    function __construct() {
        print "In BaseClass constructor\n";
        $this->airavataconfig = parse_ini_file("airavata-client-properties.ini");

        $this->transport = new TSocket($this->airavataconfig['AIRAVATA_SERVER'], $this->airavataconfig['AIRAVATA_PORT']);
        $this->transport->setRecvTimeout($this->airavataconfig['AIRAVATA_TIMEOUT']);
        $this->transport->setSendTimeout($this->airavataconfig['AIRAVATA_TIMEOUT']);

        $protocol = new TBinaryProtocol($this->transport);
        $this->transport->open();
        $this->airavataclient = new AiravataClient($protocol);

        $this->authToken = new AuthzToken();
        $this->authToken->accessToken = "";

        $this->gatewayId = $this->airavataconfig['GATEWAY_ID'];
    }

    function __destruct() {
        /** Closes Connection to Airavata Server */
        $this->transport->close();
    }

    /**
     * This function calls Airavata Launch Experiments. Inside the implementation, all the required steps such as
     *  creating an experiment and then launching is taken care of.
     *
     * @param string $limsHost - Host where LIMS is deployed.
     * @param string $limsUser - Unique user name of LIMS User
     * @param string $experimentName - Name of the Experiment - US3-AIRA, US3-ADEV ..
     * @param string $requestId - LIMS Instance concatenated with incremented request ID. Ex: uslims3_CU_Boulder_1974
     * @param string $computeCluster - Host Name of the Compute Cluster. Ex: comet.sdsc.edu
     * @param string $queue - Queue Name on the cluster
     * @param integer $cores - Number of Cores to be requested.
     * @param integer $nodes - Number of Nodes to be requested.
     * @param integer $mGroupCount - Parallel groups.
     * @param integer $wallTime - Maximum wall time of the job.
     * @param string $clusterUserName - Jureca submissions will use this value to construct the userDN. Other clusters ignore it.
     * @param string $inputFile - Path of the Input Tar File
     * @param string $outputDataDirectory - Directory path where Airavata should stage back the output tar file.
     *
     * @return array - The array will have three values: $launchStatus, $experimentId, $message
     *
     */
    function launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                        $computeCluster, $queue, $cores, $nodes, $mGroupCount, $wallTime, $clusterUserName,
                                        $inputFile, $outputDataDirectory)
    {

        $version = $this->airavataclient->getAPIVersion($this->authToken);
        echo $version .PHP_EOL;

        $projectId = fetch_projectid($this->airavataclient, $this->authToken, $this->gatewayId, $limsUser);

        echo "project id is ", $projectId, PHP_EOL;

        $experiment = create_experiment_object($projectId,$limsHost, $limsUser, $experimentName, $requestId);
        var_dump($experiment);

        $experimentId = $this->airavataclient->createExperiment($this->authToken,$this->gatewayId,$experiment);
        echo "experimentId is ", $experimentId;

        $this->airavataclient->launchExperiment($this->authToken,$experimentId,$this->gatewayId);

        $returnArray = [
            "launchStatus" => true,
            "experimentId" => $experimentId,
            "message" => "Experiment Created and Launched as Expected. No errors"
        ];

        return $returnArray;
    }
}