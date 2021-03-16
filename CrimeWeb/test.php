<?php
     
	

	include("conn.php");

	$customer_email=$_POST['customer_email'];
	$sql_search="select ID, ERROR_TYPE,STATUS from REPORT";

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



	 
	 
	/*$error_type="syntax";
	$text="some word is not right";
	$customer_email="john@ufl.edu";
	//echo gettype($username);
	//$nowtime= new DateTime();
	//$ID = $nowtime->format('Y-m-d-H-i-s');
    $ID="k530003019";
     $host = "oracle.cise.ufl.edu/orcl";
        $dbUsername = "jxia";
        $dbPassword = "HPJxjy961230";
        //create connection
        $conn =  oci_connect($dbUsername, $dbPassword, $host);
        if (!$conn) {
            $e = oci_error();
            print htmlentities($e['message']);
            exit;
        }else {
			
			$sql="insert into report(customer_email,ADMINISTRATOR_EMAIL,id, error_type, text, status,REPLY) values(:customer_email,'xiajingyi@ufl.edu',:id, :error_type, :text,'ready','')";
            
			$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
			
			oci_bind_by_name($stmt,":customer_email",$customer_email);
			oci_bind_by_name($stmt,":id",$ID);
			oci_bind_by_name($stmt,":error_type",$error_type);
			oci_bind_by_name($stmt,":text",$text);
            oci_execute ($stmt,OCI_COMMIT_ON_SUCCESS );
			oci_free_statement($stmt);
			oci_close ( $conn );*/
		
         /*   oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
            oci_free_statement ( $stmt );
            oci_close ( $conn );
            //数组编码转换
            foreach( $result as $v ) {
                $_result [] = $v;
            }
			
			header('Content-type:application/json;charset=utf-8');
			echo json_encode($_result);*/
			
			
			
       // }
    ?>