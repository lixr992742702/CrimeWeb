    <?php

    $type = $_POST['type'];
    $yearfrom = $_POST['yearfrom'];
	$yearto = $_POST['yearto'];
    if (!empty($type) || !empty($yearfrom)|| !empty($yearto)) {
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
				$sql="SELECT a1.state, a2.value-a1.value difference FROM
(SELECT r.state, count(distinct(r.msn)) value FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year='".$yearfrom."' and r.amount <> 0 and r.amount is not null
group by r.state) a1,
(SELECT r.state, count(distinct(r.msn)) value FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year='".$yearto."' and r.amount <> 0 and r.amount is not null
group by r.state) a2 
WHERE a1.state = a2.state  and (a2.value - a1.value)<= all(SELECT a2.value - a1.value FROM
(SELECT r.state, count(distinct(r.msn)) value FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and  r.year='".$yearfrom."' and r.amount <> 0 and r.amount is not null
group by r.state) a1,
(SELECT r.state, count(distinct(r.msn)) value FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."'and r.year='".$yearto."' and r.amount <> 0 and r.amount is not null
group by r.state) a2 WHERE a1.state = a2.state )";
			
            
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