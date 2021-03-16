    <?php

    $type = $_POST['type'];
    $state = $_POST['state'];
	$year = $_POST['year'];
	$sector = $_POST['sector'];

    if (!empty($type) || !empty($state)|| !empty($year)|| !empty($sector)) {
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
				$sql="SELECT r1.energy, r1.sum2/total.sum1 percentage FROM
					(SELECT c.energy, sum(to_number(r.amount)) sum2 FROM RESOURCES r, Catalog c 
					WHERE r.msn = c.msn and r.state='".$state."'  and c.sector='".$sector."' and c.type='".$type."' and r.year='".$year."'  group by c.energy) r1,
					(SELECT sum(to_number(r.amount)) sum1 FROM RESOURCES r, Catalog c WHERE r.msn = c.msn and r.state='".$state."'  
					and c.sector='".$sector."' and c.type='".$type."' and r.year='".$year."' ) total";
            
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
		
           
			for($x=0;$x<$arrlength;$x++)
			{
				$value[$x][0]=$_result[$x]["ENERGY"];
				$value[$x][1]=floatval($_result[$x]["PERCENTAGE"]);
				
			}
			
			$res['data'] = $value;
			header('Content-type:application/json;charset=utf-8');
			echo json_encode($res);
			
			
			
			
        }
    } else {
     echo "All field are required";
     die();
    }
    ?>