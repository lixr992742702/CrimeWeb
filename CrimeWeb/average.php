    <?php

    $type = $_POST['type'];
	$state = $_POST['state'];
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
			if(empty($state)){
				$sql="SELECT s1.state name, s1.minvalue FROM
(SELECT r.state, SUM(r.amount) minvalue FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."' GROUP BY r.state
HAVING SUM(r.amount) > (SELECT AVG(SUM(r.amount)) FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."'
GROUP BY r.state)) s1";
			}
			else{
				$sql="SELECT s1.sector name, s1.minvalue FROM
(SELECT c.sector, SUM(r.amount) minvalue FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."' and r.state='".$state."' and c.sector is not null GROUP BY c.sector
HAVING SUM(r.amount) > (SELECT AVG(SUM(r.amount)) FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.state = '".$state."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."' and c.sector is not null
GROUP BY c.sector)) s1";
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