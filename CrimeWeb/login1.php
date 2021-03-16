
<?php

$email = $_POST['email'];
$password = $_POST['password'];

    $host = "oracle.cise.ufl.edu/orcl";
    $dbUsername = "jxia";
    $dbPassword = "HPJxjy961230";
    //create connection
    $conn =  oci_connect($dbUsername, $dbPassword, $host);
    $sql ="SELECT * FROM USERINFO WHERE email = '".$email."' AND password = '".$password."' ";
    	$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
                oci_execute ( $stmt, OCI_DEFAULT );//执行SQL
                oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
                oci_free_statement ( $stmt );
                oci_close ( $conn );
    	foreach( $result as $v ) {
                    $_result [] = $v;
                }
				
    	if($_result[0]['EMAIL'] == $email && $_result[0]['PASSWORD'] == $password)
    	{
    		$conn1 =  oci_connect($dbUsername, $dbPassword, $host);
			$sql1 ="SELECT * FROM ADMINISTRATOR WHERE email = '".$email."' ";
			$stmt1 = oci_parse ( $conn1, $sql1 );// 配置 Oracle 语句预备执行
                oci_execute ( $stmt1, OCI_DEFAULT );//执行SQL
                oci_fetch_all ( $stmt1, $result1, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
                oci_free_statement ( $stmt1 );
                oci_close ( $conn1 );
			foreach( $result1 as $v1 ) {
                    $_result1 [] = $v1;
                }
				
			if($_result1[0]['EMAIL'] == $email){
				header('Location:analysis.html' );
			}
			else{
				header('Location:user_index.html?email='.$email );
			}
    	}
    	else
    	{
    		echo "<script language=javascript>alert('Username or password is not right！');history.back();</script>"; 
    	}
 ?>