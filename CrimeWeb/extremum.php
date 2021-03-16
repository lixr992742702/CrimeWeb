    <?php

    $type = $_POST['type'];
    $yearfrom = $_POST['yearfrom'];
	$yearto = $_POST['yearto'];
    if (!empty($type) || !empty($yearfrom) || !empty($yearto)) {
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
			$sql =  "SELECT s1.state state1, s1.minvalue minvalue, s2.state state2, s2.maxvalue maxvalue FROM
(SELECT r.state, SUM(r.amount) minvalue FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year>= '".$yearfrom."' 
and r.year<='".$yearto."' GROUP BY r.state HAVING SUM(r.amount) = 
(SELECT MIN(SUM(r.amount)) FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."' GROUP BY r.state)) s1,
(SELECT r1.state, SUM(r1.amount) maxvalue FROM RESOURCES r1, catalog c1 WHERE r1.msn = c1.msn and c1.type = '".$type."' 
and r1.year>= '".$yearfrom."' and r1.year<= '".$yearto."' GROUP BY r1.state HAVING SUM(r1.amount) = (SELECT MAX(SUM(r1.amount)) 
FROM RESOURCES r1, catalog c1 WHERE r1.msn = c1.msn and c1.type = '".$type."' and r1.year>= '".$yearfrom."' and r1.year<= '".$yearto."' GROUP BY r1.state)) s2";//sql 语句
            
			$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
            oci_execute ( $stmt, OCI_DEFAULT );//执行SQL
            oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
            oci_free_statement ( $stmt );
            oci_close ( $conn );
            //数组编码转换
            foreach( $result as $v ) {
                $_result [] = $v;
            }
			
			$time=array();
			$value=array();
           
			
			$time[0]=$_result[0]["STATE1"];
			$time[1]=$_result[0]["STATE2"];
			$value[0]=floatval($_result[0]["MINVALUE"]);
			$value[1]=floatval($_result[0]["MAXVALUE"]);
			
			
            $res['xname'] = $time;
			$res['data'] = $value;
			
			header('Content-type:application/json;charset=utf-8');
			echo json_encode($res);
			
			
			
        }
    } else {
     echo "All field are required";
     die();
    }
    ?>