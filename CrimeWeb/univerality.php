    <?php

    $type = $_POST['type'];
    $amount = $_POST['amount'];
	$number =$_POST['number'];
	$year = $_POST['year'];
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
				$sql="SELECT state, count(DISTINCT energy) as count
					FROM
					(SELECT msn,energy
					FROM catalog
					WHERE type = '".$type."' ) r1,
					resources
					WHERE resources.msn = r1.msn
					AND year = '".$year."' 
					AND to_number(amount)>'".$amount."' 
					GROUP BY state
					HAVING count(DISTINCT energy) >'".$number."' ";
            
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
				$time[$x]=$_result[$x]["STATE"];
				$value[$x]=floatval($_result[$x]["COUNT"]);
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