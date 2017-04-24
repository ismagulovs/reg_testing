<?php
function add_otvetsec($login, $fio, $school, $kol_try){
    include_once  "./././connect.php";
    $id_vpz = vpz_id();
	$login = str_replace(" ","",$login);
	$fio = str_replace(" ","",$fio);

    if((get_login($login) > 0) and ($login == '')){
        return json_encode(array("status" => "error", "text" => "Логин занят!!!"));
        exit();
    } 
    /// add valid login

    if (($test_kol_try = test_kol_try($kol_try)) === false){
        $kol_try = $kol_try;
    }else{
        return json_encode(array("status" => "error", "text" => $test_kol_try));
        exit();
    }

    if (!$login or $login == '') {
        return json_encode(array("status" => "error", "text" => "не указан Логин"));
        exit();
    }
	
	if (!$fio or $fio == '') {
        return json_encode(array("status" => "error", "text" => "не указано ФИО"));
        exit();
    }


    if (!$school || $school == '' || $school == '0') {
        return json_encode(array("status" => "error", "text" => "не указана школа"));
        exit();
    }
    $ins_user = insert_otvetsec($login, $fio, $school, $kol_try, $school, $id_vpz);

    if ($ins_user == false) {
        return json_encode(array("status" => "error", "text" => "ошибка звписи в базу!!! обратитсь к программистам"));
        exit();
    }

    return json_encode(array("status" => "ok", "text" => "пользователь добавлен"));

    exit();
}

function add_try_otvetsec($id_otvetsec, $kol_try){

    include_once  "./././connect.php";
    $id_vpz = vpz_id();
    db_connect();
    if(!$kol_try or $kol_try == '' or $kol_try < 1 or $kol_try > 100){
        return json_encode(array("status"=>"error","text"=>"неверно введено количество попыток"));
        exit();
    }

    $res = update_add_try_otvetsec( $kol_try, $id_otvetsec, $id_vpz);

    error_log($res, 0);
   // $a_text = "Добавлена попытка. id: $id_student , кол-во попыток: $kol_try";
   // insert_action_log($id_vpz, $a_text, '2');

    echo json_encode(array("status"=>"ok", "text" => $res));
}


function update_otvetsec($id, $fio, $school){

    $id_vpz = vpz_id();

    if($school == null) $school = 0;

    $fio = str_replace("  "," ",$fio);

    if($fio == ''){
        return json_encode(array("status"=>"error","text"=>"не указано ФИО"));
        exit();
    }

    update_otvetsec_db($id, $fio , $school, $id_vpz);

    return json_encode(array("status"=>"ok", "text"=>"ок"));
    exit();
}

function get_login($login)//email
{
    include_once  "./././connect.php";
    db_connect();
    $query = "SELECT login FROM users where login = '$login'";
    $result = pg_query($query);
    $result = pg_num_rows($result);
    return $result;
}
function test_kol_try($kol_try){
    if(!$kol_try or $kol_try == '' or $kol_try < 1 or $kol_try > 100){
        return "неверно введено количество попыток!!!";
    }else{
        return false;
    }
}

function vpz_id(){
    session_start();
    if(isset($_SESSION['loginID'])){
        return $_SESSION['loginID'];
    }else{
        return false;
    }
}

function insert_otvetsec($login, $fio, $school, $kol_try, $school, $id_vpz)
{
    include_once  "./././connect.php";
    db_connect();
    $pass = password(5);
    pg_query("insert into users (login, username, id_user_role, status, password) VALUES ('$login', '$fio', 6, 1, '$pass')");
    $res = pg_fetch_array(pg_query("select id from users where login = '$login'"));

    if($res['id'] > 0){
        $id_user = $res['id'];
        pg_query("insert into user_restrict (id_user, id_test_org, id_user_ppent, count_try) values
                            ($id_user, $school, $id_vpz, $kol_try)");
        return "insert into user_restrict (id_user, id_test_org, id_user_ppent, count_try) values
                            ($id_user, $school, $id_vpz, $kol_try)";
    }else{
        return false;
    }
}
function update_add_try_otvetsec($kol_try, $id_otvetsec, $id_vpz)
{
    include_once  "./././connect.php";

    $res = pg_query("update user_restrict set count_try = count_try + $kol_try where id_user = $id_otvetsec and id_user_ppent = $id_vpz");

    if(!$res){
        return true;
    }else{
        return false;
    }
}
function  update_otvetsec_db($id, $fio , $school, $id_vpz)
{
    include_once  "./././connect.php";

    $itemres = show_add_otvetsec($id_vpz, $id);
    if($itemres[0]['username'] != $fio){
        pg_query("update users set username = '$fio' where id = $id;");
    }

    if($itemres[0]['id_test_org'] != $school and $school != 0){
        pg_query("update user_restrict set id_test_org = $school where id_user = $id and id_user_ppent = $id_vpz");
    }

}

function password($n){
    $chars="1234567890aswfxvkpyt";
    $max=$n;
    $size=StrLen($chars)-1;
    $password=null;
    while($max--){
        $password.=$chars[rand(0,$size)];
    }
    return $password;
}

function show_add_otvetsec($id_vpz, $id)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "select u.id, u.login, u.username, r.id_test_org, t.name_rus from users u
                    inner join user_restrict r on r.id_user = u.id
                    left join test_org t on t.id = r.id_test_org
                where u.id = $id and r.id_user_ppent = $id_vpz";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}