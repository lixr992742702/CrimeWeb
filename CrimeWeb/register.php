
<?php

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$gender = $_POST['gender'];
$birthday = $_POST['birthday'];
$priviledge = $_POST['priviledge'];

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
			echo "<script language=javascript>alert('Username and password exist！');history.back();</script>"; 
    	}
    	else
    	{
			$conn2 =  oci_connect($dbUsername, $dbPassword, $host);
			$sql2 ="insert into CUSTOMER(email) values(:email)";
			$stmt2 = oci_parse ( $conn2, $sql2 );// 配置 Oracle 语句预备执行
			oci_bind_by_name($stmt2,":email",$email);
	
    		$conn1 =  oci_connect($dbUsername, $dbPassword, $host);
			$sql1 ="insert into USERINFO(email,username,password,gender,birthday,priviledge) values(:email,:username,:password,:gender,:birthday,:priviledge)";
			$stmt1 = oci_parse ( $conn1, $sql1 );// 配置 Oracle 语句预备执行
			oci_bind_by_name($stmt1,":email",$email);
			oci_bind_by_name($stmt1,":username",$username);
			oci_bind_by_name($stmt1,":password",$password);
			oci_bind_by_name($stmt1,":gender",$gender);
			oci_bind_by_name($stmt1,":birthday",$birthday);
			oci_bind_by_name($stmt1,":priviledge",$priviledge);
		
			
			if(oci_execute($stmt1,OCI_COMMIT_ON_SUCCESS) && oci_execute($stmt2,OCI_COMMIT_ON_SUCCESS)){
				oci_free_statement ( $stmt2 );
                oci_close ( $conn2 );
                oci_free_statement ( $stmt1 );
                oci_close ( $conn1 );
				echo "<script language=javascript>alert('successfully！');history.back();</script>"; 
			}
			else{
				oci_free_statement ( $stmt2 );
                oci_close ( $conn2 );
                oci_free_statement ( $stmt1 );
                oci_close ( $conn1 );
				echo "<script language=javascript>alert('fail！');history.back();</script>"; 
			}
			
			
			 
				
			
    	}
 ?>