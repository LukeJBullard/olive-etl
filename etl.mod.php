<?php
    /**
     * ETL Module for OliveWeb
     * Luke Bullard, May 2018
     */

    //make sure we are included securely
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); exit(0); }

    /**
     * The ETL OliveWeb Module
     */
    class MOD_etl
    {
        public function __construct()
        {
            require_once("src/Constants.php");
            require_once("src/ETLResult.php");
            require_once("src/ETLStep.php");
            require_once("src/ETLExtract.php");
            require_once("src/ETLTransform.php");
            require_once("src/ETLLoad.php");
            require_once("src/ETL.php");
        }
    }
?>