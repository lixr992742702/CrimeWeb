<?php

$host = "oracle.cise.ufl.edu/orcl";
$dbUsername = "jxia";
$dbPassword = "HPJxjy961230";



//create connection
$conn = oci_connect($dbUsername, $dbPassword, $host);
if (!$conn) {
    $e = oci_error();
    print htmlentities($e['message']);
    exit;
}
