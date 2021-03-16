
<?php

$type = $_POST['type'];
$year = $_POST['year'];
$state = $_POST['state'];
$sector = $_POST['sector'];

    $host = "oracle.cise.ufl.edu/orcl";
    $dbUsername = "jxia";
    $dbPassword = "HPJxjy961230";
    //create connection
    $conn =  oci_connect($dbUsername, $dbPassword, $host);
    $sql ="SELECT c.description, r.state,r.year,r.amount FROM CATALOG c, RESOURCES r where c.msn = r.msn and r.year = '".$year."' and c.sector='".$sector."' and c.type='".$type."' and r.state='".$state."' and r.amount>0";
	
    	$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
                oci_execute ( $stmt, OCI_DEFAULT );//执行SQL
                oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
                oci_free_statement ( $stmt );
                oci_close ( $conn );
    	foreach( $result as $v ) {
                    $_result [] = $v;
                }
				
		header('Content-type:application/json;charset=utf-8');
	    echo json_encode($_result);
				
    	
 ?>