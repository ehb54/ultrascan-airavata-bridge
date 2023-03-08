<?php

namespace SCIGAP;


interface AiravataWrapperInterface
{
    /**
     * This function calls Airavata Launch Experiments. Inside the implementation, all the required steps such as
     *  creating an experiment and then launching is taken care of.
     *
     * @param string $limsHost - Host where LIMS is deployed.
     * @param string $limsUser - Unique user name of LIMS User. Reported to XSEDE for gateway user tracking.
     * @param string $experimentName - Name of the Experiment - US3-AIRA, US3-ADEV ..
     * @param string $requestId - LIMS Instance concatenated with incremented request ID. Ex: uslims3_CU_Boulder_1974
     * @param string $computeCluster - Host Name of the Compute Cluster. Ex: comet.sdsc.edu
     * @param string $queue - Queue Name on the cluster
     * @param integer $cores - Number of Cores to be requested.
     * @param integer $nodes - Number of Nodes to be requested.
     * @param integer $mGroupCount - Parallel groups.
     * @param integer $wallTime - Maximum wall time of the job.
     * @param string $clusterUserName - Juelich’s clusters will use this to submit job as the specified user. Other clusters ignore it.
     * @param string $clusterScratch - Cluster scratch for Juelich clusters, Others ignore it.
     * @param string $clusterAllocationAccount - override cluster allocation project account number
     * @param string $inputFile - Path of the Input Tar File
     * @param string $outputDataDirectory - Directory path where Airavata should stage back the output tar file.
     * @param integer $memoryreq - Optional memory requirement in megabytes. Pass 0 if needed to be skipped
     *
     * @return array - The array will have three values: $launchStatus, $experimentId, $message
     *
     */
    function launch_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                        $computeCluster, $queue, $cores, $nodes, $mGroupCount, $wallTime, $clusterUserName,
                                        $clusterScratch, $clusterAllocationAccount, $inputFile, $outputDataDirectory, $memoryreq);

    /**
     * This function calls Airavata Launch Experiments. Inside the implementation, all the required steps such as
     *  creating an experiment and then launching is taken care of.
     *
     * @param string $limsHost - Host where LIMS is deployed.
     * @param string $limsUser - Unique user name of LIMS User. Reported to XSEDE for gateway user tracking.
     * @param string $experimentName - Name of the Experiment - US3-AIRA, US3-ADEV ..
     * @param string $requestId - LIMS Instance concatenated with incremented request ID. Ex: uslims3_CU_Boulder_1974
     * @param array $computeClusters - Host Name of the Compute Cluster. Ex: comet.sdsc.edu
     * @param string $inputFile - Path of the Input Tar File
     * @param string $outputDataDirectory - Directory path where Airavata should stage back the output tar file.
     * @param integer $memoryreq - Optional memory requirement in megabytes. Pass 0 if needed to be skipped
     *
     * @return array - The array will have three values: $launchStatus, $experimentId, $message
     *
     */

    function launch_autoscheduled_airavata_experiment($limsHost, $limsUser, $experimentName, $requestId,
                                                       $computeClusters, $inputFile, $outputDataDirectory);

    /**
     * This function calls fetches Airavata Experiment Status.
     *
     * @param string $experimentId - Id of the Experiment.
     *
     * @return string - Status of the experiment.
     *
     */
    function get_experiment_status($experimentId);

    /**
     * This function calls fetches errors from an Airavata Experiment.
     *
     * @param string $experimentId - Id of the Experiment.
     *
     * @return array - The array will have any errors if recorded.
     *
     */
    function get_experiment_errors($experimentId);

    /**
     * This function calls terminates previously launched Airavata Experiment.
     *
     * @param string $experimentId - Id of the Experiment to be terminated.
     *
     * @return array - The array will have two values: $cancelStatus, $message
     *
     */
    function terminate_airavata_experiment($experimentId);

}