    <?php

    $type = $_POST['type'];
    $amount = $_POST['amount'];
	$year = $_POST['year'];
	//$type ="use";
	//$amount =700;
	//$year =2000;
    if (!empty($type) || !empty($amount)|| !empty($year)) {
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
				$sql="SELECT DISTINCT c.energy
				FROM resources r ,catalog c
				WHERE r.msn = c.msn
				AND r.year = '".$year."'
				AND r.amount > 0
				AND c.type = '".$type."'
				AND NOT EXISTS ((SELECT DISTINCT sector
                FROM catalog
                WHERE year = '".$year."')
                MINUS
                (SELECT DISTINCT sector
                FROM resources,catalog
                WHERE to_number(resources.amount) > '".$amount."'
                AND r.msn = resources.msn
                AND year = '".$year."'))";
            
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
    } else {
     echo "All field are required";
     die();
    }
    ?>