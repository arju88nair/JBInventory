<?php

// Create connection
$conn = oci_connect("memp", "memp", "10.0.0.15");// Check connection
if(! $conn ) {
      die('Could not connect: ' . mysql_error());
   }

?>