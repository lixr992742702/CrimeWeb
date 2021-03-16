<?php
include("conn.php");

$error_type=$_POST['error_type'];
$text=$_POST['text'];
$username=$_POST['user_email'];
$nowtime= new DateTime();
$ID = $nowtime->format('Y-m-d-H-i-s');

$sql= "insert into report(customer_email,ADMINISTRATOR_EMAIL,id, error_type, text, status,REPLY) values(:customer_email,NULL,:id, :error_type, :text,'ready',NULL)";
$stmt = oci_parse ( $conn, $sql );
oci_bind_by_name($stmt, ":customer_email", $username);
oci_bind_by_name($stmt, ":id", $ID);
oci_bind_by_name($stmt, ":error_type", $error_type);
oci_bind_by_name($stmt, ":text", $text);

if(oci_execute ( $stmt, OCI_COMMIT_ON_SUCCESS)){
    echo "success";
}//执行SQL

oci_free_statement ( $stmt );
oci_close ( $conn );
header('location: report.html');


 ?>

