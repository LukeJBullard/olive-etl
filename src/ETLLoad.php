<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

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
?>