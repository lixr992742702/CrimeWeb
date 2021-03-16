<?php
	 $type = $_POST['type'];
    $state = $_POST['state'];
    if (!empty($type) || !empty($state)) {
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
				$sql="SELECT ROUND(sum*rate,2) y2018,ROUND(sum*power(rate,2),2) y2019,ROUND(sum*power(rate,3),2) y2020,ROUND(sum*power(rate,4),2) y2021,ROUND(sum*power(rate,5),2) y2022
						FROM (SELECT year, sum(amount) sum
						FROM 
						(SELECT msn
						FROM catalog
						WHERE type = '".$type."') NATURAL JOIN resources 
						WHERE year= 2017
						AND state = '".$state."'
						GROUP BY year
						ORDER BY year asc),
						(SELECT avg(increase_rate) rate
						FROM
						(WITH R(year,sum) AS
						(SELECT year, sum(amount) sum
						FROM 
						(SELECT msn
						FROM catalog
						WHERE type = '".$type."') NATURAL JOIN resources 
						WHERE 1998<=year
						AND year<=2017
						AND state = '".$state."'
						GROUP BY year
						ORDER BY year asc)
						SELECT r1.year,r2.sum/r1.sum increase_rate
						FROM R r1,R r2
						WHERE r1.year = r2.year-1))";
            
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
			$cars=array();

			/*for($x=0;$x<$arrlength;$x++)
			{
				$cars[$x]=$_result[$x]["Y2018"];
			}*/
			$cars[0]=floatval($_result[0]["Y2018"]);
			$cars[1]=floatval($_result[0]["Y2019"]);
			$cars[2]=floatval($_result[0]["Y2020"]);
			$cars[3]=floatval($_result[0]["Y2021"]);
			$cars[4]=floatval($_result[0]["Y2022"]);
			$res['name'] = "2018~2022";
            $res['data'] = $cars;
			
			//header('Content-type:application/json;charset=utf-8');
			echo json_encode($res);
			
			
			
        }
    } else {
     echo "All field are required";
     die();
    }
    ?>