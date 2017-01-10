<?php  
$connect = odbc_connect('NFe','odbc','odbc') or die(E_USER_ERROR);	
if($connect){
echo "sim";
}else{
	echo "nao";
}	

$sql = "SELECT ID FROM NFE";
$qry = odbc_exec($connect, $sql);			

///echo "<p>total: ".$total = odbc_num_rows($qry);

$x=0;
while($rs = odbc_fetch_array($qry)){
    $x++;	
	echo "<br>Ids: ".$rs['ID'];
}

?>

