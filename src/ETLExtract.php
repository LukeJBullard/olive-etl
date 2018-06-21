<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

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
?>