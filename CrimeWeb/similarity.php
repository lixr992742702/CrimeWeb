    <?php

    $type = $_POST['type'];
	$state1 = $_POST['state1'];
	$state2 = $_POST['state2'];
    $yearfrom = $_POST['yearfrom'];
	$yearto = $_POST['yearto'];
    if (!empty($type) || !empty($state1)|| !empty($state2)|| !empty($yearfrom)|| !empty($yearto)) {
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
			
			$sql = "SELECT s1.energy energy,abs(s1.s1value - s2.s2value) difference FROM
(SELECT c.energy, SUM(r.amount) s1value FROM RESOURCES r, catalog c WHERE r.msn = c.msn and 
c.type = '".$type."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."' and r.state = '".$state1."' and r.amount <>0 group by c.energy) s1,
(SELECT c.energy, SUM(r.amount) s2value FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' 
and r.year>= '".$yearfrom."' and r.year<='".$yearto."' and r.state = '".$state2."' and r.amount <>0 group by c.energy)s2
WHERE s1.energy = s2.energy and abs(s1.s1value - s2.s2value)<=500";
            
			$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
            oci_execute ( $stmt, OCI_DEFAULT );//执行SQL
            oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
            oci_free_statement ( $stmt );
            oci_close ( $conn );
            //数组编码转换
            foreach( $result as $v ) {
                $_result [] = $v;
            }
			
			$arrlength=count($_result);
			$time=array();
			$value=array();
           
			for($x=0;$x<$arrlength;$x++)
			{
				$time[$x]=$_result[$x]["ENERGY"];
				$value[$x]=floatval($_result[$x]["DIFFERENCE"]);
			}
			
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