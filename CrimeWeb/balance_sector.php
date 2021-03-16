    <?php

    $type = $_POST['type'];
	$state = $_POST['state'];
	$sector = $_POST['sector'];
    $yearfrom = $_POST['yearfrom'];
	$yearto = $_POST['yearto'];
    if (!empty($type) || !empty($state) || !empty($yearfrom)|| !empty($yearto)) {
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
			$sql="SELECT sum(a1.value) top5, sum(a2.value) rest,  sum(a1.value)-sum(a2.value) difference FROM (SELECT s1.energy, s1.value FROM
(SELECT c.energy, SUM(r.amount) value, RANK() OVER(ORDER BY SUM(r.amount) DESC) rank FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' 
and r.year>= '".$yearfrom."' and r.year<='".$yearto."' and r.state = '".$state."'  and c.sector = '".$sector."' group by c.energy order by SUM(r.amount) DESC) s1
WHERE rank <= 5) a1,(SELECT s1.energy, s1.value FROM(SELECT c.energy, SUM(r.amount) value, RANK() OVER(ORDER BY SUM(r.amount) DESC) rank 
FROM RESOURCES r, catalog c WHERE r.msn = c.msn and c.type = '".$type."' and r.year>= '".$yearfrom."' and r.year<='".$yearto."' and r.state = '".$state."' 
and c.sector = '".$sector."' group by c.energy order by SUM(r.amount) DESC) s1 WHERE rank > 5) a2";
            
			$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
            oci_execute ( $stmt, OCI_DEFAULT );//执行SQL
            oci_fetch_all ( $stmt, $result, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW );
            oci_free_statement ( $stmt );
            oci_close ( $conn );
            //数组编码转换
            foreach( $result as $v ) {
                $_result [] = $v;
            }
			
			$value[0][0]="TOP5";
			$value[0][1]=floatval($_result[0]["TOP5"]);
			$value[1][0]="REST";
			$value[1][1]=floatval($_result[0]["REST"]);
			$value[2][0]="DIFFERENCE";
			$value[2][1]=floatval($_result[0]["DIFFERENCE"]);
				
			
			
			$res['data'] = $value;
			
			header('Content-type:application/json;charset=utf-8');
			echo json_encode($res);
			
			
        }
    } else {
     echo "All field are required";
     die();
    }
    ?>