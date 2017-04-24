<?php
function add_school($id_obl, $id_raion, $name_kaz, $name_rus){
    if(!$id_obl or $id_obl == '' or $id_obl == '0'){
        return json_encode(array("status"=>"error","text"=>"не выбрана область"));
    }else{
        $id_obl = $id_obl;
    }

    if(!$id_raion or $id_raion == '' or $id_raion == '0'){
        return json_encode(array("status"=>"error","text"=>"не выбран район"));
    }else{
        $id_raion = $id_raion;
    }

    if(!$name_kaz or $name_kaz == ''){
        return json_encode(array("status"=>"error","text"=>"введите наименование на казахском"));
    }else{
        $name_kaz = $name_kaz;
    }

    if(!$name_rus or $name_rus == ''){
        return json_encode(array("status"=>"error","text"=>"введите наименование на русском"));
    }else{
        $name_rus = $name_rus;
    }

    $ins_school = insert_school($id_obl , $id_raion , $name_kaz , $name_rus);
    if($ins_school == false){
        return json_encode(array("status"=>"error", "text"=> "ошибка звписи в базу!!! обратитсь к программистам"));
    }

    return json_encode(array("status"=>"ok","text"=>"учебное заведение добавлено в базу"));
}

function insert_school($id_obl, $id_raion, $name_kaz, $name_rus)
{
    include_once  "././connect.php";
    db_connect();
    $query = "insert into test_org (name_rus, name_kaz, id_obl, id_raion)
	        	VALUES ('$name_rus', '$name_kaz', $id_obl, $id_raion);";
    $result = pg_query($query);
    return $result;
}