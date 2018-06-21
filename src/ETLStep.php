<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

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
?>