<?php
include(__DIR__ .'/../connect.php');

$type = $_POST['type'];

if($type == 'raion'){

	$id_obl = $_POST['id_obl'];
	$query = "select id_raion, name_rus, name_kaz from spr_raion WHERE id_obl = $id_obl";
	db_connect();
	$regs = pg_query($query);
	if ($regs) {
		$num = pg_num_rows($regs);      
		$i = 0;
		while ($i < $num) {
		   $raion[$i] = pg_fetch_array($regs);   
		   $i++;
		}     
		$result = array('raion'=>$raion);  
	}else {
		$result = array('type'=>'error');
	}
	
}elseif($type == 'school'){

	$id_obl = $_POST['id_obl'];
	$id_raion = $_POST['id_raion'];
	$query = "select id, name_rus, name_kaz from test_org WHERE id_obl = $id_obl and id_raion = $id_raion";
	db_connect();
	$regs = pg_query($query);
	if ($regs) {
		$num = pg_num_rows($regs);      
		$i = 0;
		while ($i < $num) {
		   $school[$i] = pg_fetch_array($regs);   
		   $i++;
		}
        if(!isset($school)){
            $result = array('type'=>'error1');
        }else{
            $result = array('school'=>$school);
        }
	}else {
		$result = array('type'=>'error');
	}
	
}
echo json_encode($result);
