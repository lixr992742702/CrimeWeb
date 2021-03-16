
<?php

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$gender = $_POST['gender'];
$priviledge = $_POST['priviledge'];

//$email = "xiangrui@ufl.edu";
//$password = "000";
//$username = "haoran";
//$gender = "M";
//$priviledge = 1;


    $host = "oracle.cise.ufl.edu/orcl";
    $dbUsername = "jxia";
    $dbPassword = "HPJxjy961230";
    //create connection
    $conn =  oci_connect($dbUsername, $dbPassword, $host);
    $sql ="update USERINFO u
  set u.password = '".$password."',
      u.gender = '".$gender."',
      u.priviledge = '".$priviledge."',
      u.username= '".$username."' 
where u.email = '".$email."'";

    	$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
                if(oci_execute ( $stmt,  OCI_COMMIT_ON_SUCCESS )){
					echo "<script language=javascript>alert('change successfully！');history.back();</script>"; 
				}//执行SQL
				else{
					echo "<script language=javascript>alert('fail！');history.back();</script>"; 
				}
                oci_free_statement ( $stmt );
                oci_close ( $conn );
 ?>