<?php

include("conn.php");

$customer_email=$_POST['customer_email'];
$sql_search="select ID, ERROR_TYPE,STATUS,REPLY from REPORT where customer_email= '".$customer_email."'";

$stmt = oci_parse ( $conn, $sql_search );// 配置 Oracle 语句预备执行
oci_execute ( $stmt, OCI_DEFAULT );//执行SQL
oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
oci_free_statement ( $stmt );
oci_close ( $conn );
//数组编码转换
foreach( $result as $v ) {
    $_result [] = $v;
}

header('Content-type:application/json;charset=utf-8');
echo json_encode($_result);


 ?>