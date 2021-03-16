
<?php

$email = $_POST['email'];
$id = $_POST['id'];
$text = $_POST['description'];
    $host = "oracle.cise.ufl.edu/orcl";
    $dbUsername = "jxia";
    $dbPassword = "HPJxjy961230";
    //create connection
    $conn =  oci_connect($dbUsername, $dbPassword, $host);
    $sql="update Report r
  set r.status = 'done',
      r.reply= '".$text."' 
where r.customer_email = '".$email."' and r.id='".$id."'";

    	$stmt = oci_parse ( $conn, $sql );// 配置 Oracle 语句预备执行
                if(oci_execute ( $stmt,  OCI_COMMIT_ON_SUCCESS )){
					echo "<script language=javascript>alert('change successfully！');history.back();</script>"; 
				}//执行SQL
				else{
					echo "<script language=javascript>alert('fail');history.back();</script>"; 
				}
                oci_free_statement ( $stmt );
                oci_close ( $conn );
   
    //$sql ="update REPORT r set r.reply = '".$description."', r.status= '".$status."'  where r.customer_email = '".$email."' and r.id = '".$id."'";
    
    	
 ?>