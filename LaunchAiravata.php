<?php

namespace SCIGAP;


class LaunchAiravata implements AiravataWrapper
{

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
     * @param integer $mGroupCount - Parellel groups.
     * @param integer $wallTime - Maximum wall time of the job.
     * @param string $inputFile - Path of the Input Tar File
     * @param string $outputDataDirectory - Directory path where Airavata should stage back the output tar file.
     *
     * @return array
     *
     */
    function launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                        $computeCluster, $queue, $cores, $nodes, $mGroupCount, $wallTime,
                                        $inputFile, $outputDataDirectory)
    {
        // TODO: Implement launch_airavata_experiment() method.
    }
}