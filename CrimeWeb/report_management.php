    <?php
	
      $email = $_POST['email'];
	  $id = $_POST['id'];
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
				$sql="SELECT r.customer_email, r.id, r.error_type, r.text FROM REPORT r WHERE r.customer_email='".$email."' and r.id='".$id."'";
			                
			$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
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
			
        }
    ?>