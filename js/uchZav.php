<?php
include_once 'connect.php';
$raion_id = @intval($_GET['raion_id']);
//$country_id = 3159;

$regs = pg_query("SELECT * FROM school WHERE id_raion = $raion_id;");
 
if ($regs) {
    $num = pg_num_rows($regs);      
    $i = 0;
    while ($i < $num) {
       $school[$i] = pg_fetch_array($regs);   
       $i++;
    }     
    $result = array('school'=>$school);  
}
else {
	$result = array('type'=>$regs);
}

//echo "<pre>";
//print_r ($result);
//echo "</pre>";
print json_encode($result); 
//print var_dump($result)
?>