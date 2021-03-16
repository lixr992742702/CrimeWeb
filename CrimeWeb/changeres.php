
<?php

$msn = $_POST['msn'];
$year = $_POST['year'];
$state = $_POST['state'];
$amount = $_POST['amount'];


    $host = "oracle.cise.ufl.edu/orcl";
    $dbUsername = "jxia";
    $dbPassword = "HPJxjy961230";
    //create connection
    $conn =  oci_connect($dbUsername, $dbPassword, $host);
    $sql ="update RESOURCES r
			set r.amount = '".$amount."'
			where r.msn = '".$msn."' and r.year ='".$year."' and r.state = '".$state."'";
			
    	$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
                if(oci_execute ( $stmt, OCI_COMMIT_ON_SUCCESS )){
					echo "<script language=javascript>alert('change successfully！');history.back();</script>"; 
				}//执行SQL
				else{
					echo "<script language=javascript>alert('fail');history.back();</script>"; 
				}
                oci_free_statement ( $stmt );
                oci_close ( $conn );
 ?>