<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */
    
    /**
     * A definable, runnable, ETL
     */
    class ETL
    {
        private $m_extract;
        private $m_transform;
        private $m_load;
        private $m_status;

        /**
         * Constructor for an ETL
         * 
         * @param ETLExtract $a_extract The Extract step
         * @param ETLTransform $a_transform The Transform step
         * @param ETLLoad $a_load The Load step
         */
        public function __construct($a_extract,$a_transform,$a_load)
        {
            //if extract invalid
            if ($a_extract->getStepType() != ETL_EXTRACT)
            {
                $this->m_status = (new ETLResult())->setStatus(ETL_FAILED,"Extract Step Invalid");
                return;
            }
            //if transform invalid
            if ($a_transform->getStepType() != ETL_TRANSFORM)
            {
                $this->m_status = (new ETLResult())->setStatus(ETL_FAILED,"Transform Step Invalid");
                return;
            }
            //if load invalid
            if ($a_load->getStepType() != ETL_LOAD)
            {
                $this->m_status = (new ETLResult())->setStatus(ETL_FAILED,"Load Step Invalid");
                return;
            }
            $this->m_extract = $a_extract;
            $this->m_transform = $a_transform;
            $this->m_load = $a_load;
            $this->m_status = (new ETLResult())->setStatus(ETL_SUCCESS);
        }

        /**
         * Runs the ETL sequence
         * 
         * @return ETLResult The result of running the ETL sequence
         */
        public function run()
        {
            $extractResult = $this->m_extract->run();
            if ($extractResult->getCode() == ETL_FAILED)
            {
                //extract failed
                $this->m_status = $extractResult;
                return (new ETLResult())->setStatus(ETL_FAILED,"Extract Failed");
            }

            $transformResult = $this->m_transform->run($this->m_extract->getData());
            if ($transformResult->getCode() == ETL_FAILED)
            {
                //transform failed
                $this->m_status = $transformResult;
                return (new ETLResult())->setStatus(ETL_FAILED,"Transform Failed");
            }

            $loadResult = $this->m_load->run($this->m_transform->getData());
            $this->m_status = $loadResult;
            if ($loadResult->getCode() == ETL_FAILED)
            {
                //load failed
                return (new ETLResult())->setStatus(ETL_FAILED,"Load Failed");
            }

            //return success
            return (new ETLResult())->setStatus(ETL_SUCCESS);
        }

        /**
         * Gets the latest status of the ETL
         * 
         * @return ETLResult The last saved status of the ETL
         */
        public function getStatus()
        {
            return $this->m_status;
        }

        /**
         * Displays debugging information about the ETL sequence and the status of it's last run
         */
        public function displayDebugInfo()
        {
            if ($this->getStatus()->getCode() == ETL_SUCCESS)
            {
                echo "Success!";
            } else {
                switch ($this->getStatus()->getCode())
                {
                    case ETL_FAILED:
                        echo "Failed!" . "<br />" . $this->getStatus()->getMessage();
                        break;
                    default:
                        echo "Failed- Unknown Response Code: " . $this->getStatus()->getCode() . "<br />";
                        break;
                }
            }
        }
    }
?>