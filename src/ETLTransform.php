<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

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
?>