    <?php

    $type = $_POST['type'];
	$state = $_POST['state'];
    $sector = $_POST['sector'];
	$year = $_POST['year'];
    if (!empty($type) || !empty($state)|| !empty($year)) {
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
			if(empty($sector)){
				
				$sql="SELECT state name, sum(amount) total
						FROM
						(SELECT msn
						FROM catalog
						WHERE type = '".$type."') r1,
						resources
						WHERE resources.msn = r1.msn
						AND year = '".$year."'
						AND state ='".$state."'
						GROUP BY state";
				
			}
			else{
				$sql="SELECT r1.sector name, sum(amount) total
						FROM
						(SELECT msn, sector
						FROM catalog
						WHERE type = '".$type."' and sector='".$sector."') r1,
						resources
						WHERE resources.msn = r1.msn
						AND year = '".$year."'
						AND state ='".$state."'
						GROUP BY r1.sector";
			}
            
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