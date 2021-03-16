    <?php

    $type = $_POST['type'];
    $state = $_POST['state'];
	$yearfrom = $_POST['yearfrom'];
	$yearto = $_POST['yearto'];


    if (!empty($type) || !empty($state)|| !empty($yearfrom)|| !empty($yearto)) {
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
				$sql="Select count(*) num, r.year from resources r, Catalog c where (r.amount<=0 or r.amount=null)
						and r.msn=c.msn and c.type='".$type."' and r.year>='".$yearfrom."' and r.year<='".$yearto."' 
						and r.state='".$state."' group by r.year order by r.year";
            
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
				$time[$x]=$_result[$x]["YEAR"];
				$value[$x]=floatval($_result[$x]["NUM"]);
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