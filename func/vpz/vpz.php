<?php

$id_vpz = vpz_id();
if($id_vpz != false) {

    $array_vpz = get_user_id($id_vpz);

    $cnt_try = count_try($id_vpz);
	if($cnt_try != false){
		if ($cnt_try[0]['stat_not_0'] == null) {
			$stat_not_0 = 0;
		} else {
			$stat_not_0 = $cnt_try[0]['stat_not_0'];
		}
		if ($cnt_try[0]['stat0'] == null) {
			$stat0 = 0;
		} else {
			$stat0 = $cnt_try[0]['stat0'];
		}
	}else{
		$stat0 = 0;
		$stat_not_0 = 0;
	}

    $array_obl = get_obl();
    $array_select = '';

    foreach ($array_obl as $item):
        if ($item['id'] == $array_vpz[0]['id']){
            $selected = 'selected';
        }else{
            $selected = '';
        }
        $array_select .= '<option value="' . $item['id'] . '"  '.$selected.' >' . $item['name_rus'] . '</option>';
    endforeach;

    $data = array(
        'try' => 'Активные попытки: ' . $stat0 . '<br>Использованные попытки: ' . $stat_not_0 . '<br>Итого: ' . ($stat0 + $stat_not_0) . '<br>',
        'select' => $array_select
    );
    $result = $data;

    return $result;
}

    function vpz_id(){
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(isset($_SESSION['loginID'])){
            return $_SESSION['loginID'];
        }else{
            return false;
        }
    }

    function get_user_id($id)//вывод ппент (авторизация)
    {
        include_once  "./././connect.php";
        db_connect();
        $query = "SELECT id, username FROM users where id = $id";
        $result = pg_query($query);
        $result = db_result_to_array($result);
        return $result;
    }

    function count_try($id_vpz)//вывод id_obl, id_raion, id_uch_zav
    {
        include_once  "./././connect.php";
        db_connect();
        $query = "select s.id_user,
            sum(CASE WHEN s.id_test_status = 0 THEN 1 else 0 END) as stat0,
            sum(CASE WHEN s.id_test_status <> 0 THEN 1 else 0 END) as stat_not_0
             from student_test s
            where s.id_user = $id_vpz
            group by 1";
        $result = pg_query($query);
        if(pg_num_rows($result) > 0){
			$result = db_result_to_array($result);
			return $result;
		}else{
			return false;
		}
    }

    function get_obl()
    {
        include_once  "./././connect.php";
        db_connect();
        $query = "select id, name_rus, name_kaz from public.spr_obl order by 1";
        $result = pg_query($query);
        $result = db_result_to_array($result);
        return $result;

    }

