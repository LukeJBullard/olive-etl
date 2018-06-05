<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

    //make sure we are included securely
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); exit(0); }

    const ETL_EXTRACT = 1;
    const ETL_TRANSFORM = 2;
    const ETL_LOAD = 3;
    const ETL_STEP = 4;

    const ETL_FAILED = -1;
    const ETL_SUCCESS = 1;

    /**
     * A result that can be returned from an ETL
     */
    class ETLResult
    {
        private $m_status;
        private $m_error;

        /**
         * Retrieve the status/error code of the result
         * 
         * @return Int The status code
         */
        public function getCode()
        {
            return $this->m_status;
        }

        /**
         * Retrieve the error message of the result
         * 
         * @return String The error message
         */
        public function getMessage()
        {
            return $this->m_error;
        }

        /**
         * Set the code and error message of the result
         * 
         * @param Int $a_status The status or error code
         * @param String $a_error The error message to set (optional, blank if omitted)
         * @return ETLResult The ETLResult with the set code and message
         */
        public function setStatus($a_status,$a_error="")
        {
            $this->m_status = $a_status;
            $this->m_error = $a_error;
            return $this;
        }
    }

    /**
     * A base class of an ETL Step
     */
    class ETLStep
    {
        private $m_runFunction;
        protected $m_stepType;
        private $m_data;

        /**
         * Runs the step
         * 
         * @param Any $a_data The data to send to the step. Optional.
         * @return ETLResult The result of running the step
         */
        public function run($a_data=null)
        {
            return ($this->m_runFunction)($this,$a_data);
        }

        /**
         * Constructor for ETLStep
         * 
         * @param Function $a_runFunction The function that is called when running the ETLStep
         */
        public function __construct($a_runFunction)
        {
            $this->m_runFunction = $a_runFunction;
            $this->m_stepType = ETL_STEP;
        }

        /**
         * Retrieves the type of step this is
         * 
         * @return String The type of step this is
         */
        public function getStepType()
        {
            return $this->m_stepType;
        }

        /**
         * Sets the data of the step
         * 
         * @param Any $a_data The data for the step
         */
        public function setData($a_data)
        {
            $this->m_data = $a_data;
        }

        /**
         * Retrieves the step's data 
         * 
         * @return Any The ETLStep's Data
         */
        public function getData()
        {
            return $this->m_data;
        }
    }

    /**
     * An Extract Step of an ETL
     */
    class ETLExtract extends ETLStep
    {
        /**
         * Constructor for an Extract Step of an ETL
         * 
         * @param Function $a_runFunction The function to call when running the step
         */
        public function __construct($a_runFunction)
        {
            parent::__construct($a_runFunction);
            $this->m_stepType = ETL_EXTRACT;
        }
    }

    /**
     * A Transform step of an ETL
     */
    class ETLTransform extends ETLStep
    {
        /**
         * Constructor for a Transform step of an ETL
         * 
         * @param Function $a_runFunction The function to call when running the step
         */
        public function __construct($a_runFunction)
        {
            parent::__construct($a_runFunction);
            $this->m_stepType = ETL_TRANSFORM;
        }
    }

    /**
     * A Load step of an ETL
     */
    class ETLLoad extends ETLStep
    {
        /**
         * Constructor for a Load step of an ETL
         * 
         * @param Function $a_runFunction The function to call when running the step
         */
        public function __construct($a_runFunction)
        {
            parent::__construct($a_runFunction);
            $this->m_stepType = ETL_LOAD;
        }
    }

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

    /**
     * The ETL OliveWeb Module
     */
    class MOD_etl
    {}
?>