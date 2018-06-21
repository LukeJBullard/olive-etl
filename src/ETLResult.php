<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

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
?>