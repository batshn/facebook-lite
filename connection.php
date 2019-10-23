<?php
    include_once("vendor/autoload.php");
    try {
    // $client = new MongoDB\Client("mongodb://mongo:27020");
        $client = new MongoDB\Client("mongodb://localhost:27017");
    } 

    catch (MongoDB\Driver\Exception\Exception $e) {

        $filename = basename(__FILE__);
        echo "Warning ! Connection cannot be estabilshed.\n";
    }
?>
