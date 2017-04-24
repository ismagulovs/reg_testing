<?php

function add_student($iinp, $fam, $name, $fath, $email, $kol_try, $iinchb)
{
    include_once  "./././connect.php";
    session_start();
    $massage = '';
    if ($iinchb === 'true') {

        $nppz = sprintf("%03d", $_SESSION['loginID']);

        $ndate = date('ym');

        $max_id = test_gen_id_iin($nppz . $ndate);
        if ($max_id == null) $max_id = 0;
        do {
            $max_id = $max_id + 1;
            $gen_iin = '1' . $nppz . $ndate . sprintf("%04d", $max_id);
            $test_geniin = test_gen_iin($gen_iin);
        } while ($test_geniin === false);
        $iin = $gen_iin;

    } else {//---------проверка ИИНа -////////////////////////====----------------------------
        $iin = preg_replace('~ +~', ' ', $iinp);

        $res = getUser($iin);
        $massage = '';
        if (count($res) > 0) { //если студент с таким иином уже есть...
            $id_student = $res[0]['id'];
            $res_stud_test = get_stud_test($id_student, $_SESSION['loginID']);//првоеряем есть ли иин в ппент
            if ($res_stud_test > 0) {
                return json_encode(array("status" => "error", "text" => "пользователь с таким ИИН уже зарегестрирован у вас в таблице"));
                exit();
            }
            $massage = "пользователь уже был зарегестрирован в другом ППЕНТ";
        } else {
            if (strlen($iin) != 12 || valid_nn($iin) == false || $iin == null || $iin == ' ' || is_numeric($iin) == false || $iin == 0) {
                return json_encode(array("status" => "error", "text" => "неверный ИИН"));
                exit();
            }
        }
    }

    if (($test_kol_try = test_kol_try($kol_try)) === false){
        $kol_try = $kol_try;
    }else{
        return json_encode(array("status" => "error", "text" => $test_kol_try));
        exit();
    }


    if ($massage != "пользователь уже был зарегестрирован в другом ППЕНТ") {
        //------------проверка заполнения данных------------------
        if (!$fam or $fam == '') {
            return json_encode(array("status" => "error", "text" => "не указана фамилия"));
            exit();
        } else {
            $fam = $fam;
        }

        if (!$name or $name == '') {
            return json_encode(array("status" => "error", "text" => "не указано ИМЯ"));
            exit();
        } else {
            $name = $name;
        }

        if (!$fath or $fath == '') $fath = ' '; else $fath = $fath;


        $email = str_replace(" ", "", $email);
        if (!$email or $email == '') {
            $email = 'no';
        } else {
                if (email_format($email) == true) {
                    $email = strtolower($email);
                } else {
                    return json_encode(array("status" => "error", "text" => "email введен неверно"));
                    exit();
                }
        }


        $ins_user = insert_user($fam, $name, $fath, $iin, $email);
        if ($ins_user == false) {
            return json_encode(array("status" => "error", "text" => "ошибка звписи в базу!!! обратитсь к программистам"));
            exit();
        }
        $res = getUser($iin);
        $id_student = $res[0]['id'];
        $massage = "пользователь добавлен";

    }

///добавление попыток в student test----------------------------
    $pass = "Пароли на вход:<br> ";
    $text = "Для прохождения тестирования зайдите на сайт <a href='https://prob-voudso.testcenter.kz'>https://prob-voudso.testcenter.kz</a><br>Для входа используйте свой ИИН: " . $iin . "<br>";

    for ($i = 0; $i < $kol_try; $i++) {
        $password = password(8);
        $t_pass = strpos($pass, $password);
        if ($t_pass == false) {
            $i++;//нужно!!!!
            $pass .= $i . '. ' . $password . '<br>';
            $i--;
            $ins_stud_test = insert_student_test($id_student, $password, $_SESSION['loginID']);
        } else {
            $i = $i - 1;
        }
    }

    $a_text = "Добавлен тестируемый id: $id_student , кол-во попыток: $kol_try";
    insert_action_log($_SESSION['loginID'], $a_text, '1');

    if (isset($email)) {
        if ($email != 'no') {
            $res_mail = send_email($email, $pass, $text);
        } else {
            $res_mail = 'no_mail';
        }
    }

    return json_encode(array("status" => "ok", "id" => $id_student, "text" => $massage, 'email' => 'ok'));
    exit();
}

function add_try($id_student, $kol_try){

    include_once  "./././connect.php";
    session_start();
    $id_vpz = $_SESSION['loginID'];
    db_connect();
    if (($test_kol_try = test_kol_try($kol_try)) === false){
        $kol_try = $kol_try;
    }else{
        return json_encode(array("status" => "error", "text" => $test_kol_try));
        exit();
    }

    $pass = "Пароли на вход:<br>";
    $text = "Для прохождения тестирования пройдите на сайт <a href='https://prob-voudso.testcenter.kz'>https://prob-voudso.testcenter.kz</a><br>Для входа используйте свой ИИН.<br>";

    $res_pass = show_pass($id_student);
    $i = 0;
    foreach($res_pass as $item):
        $i++;
        $pass .= '  '.$i.' - '.$item['test_pass'].'<br>';
    endforeach;

    for($i = 0; $i < $kol_try; $i++){
        $password = password(8);
        $t_pass = strpos($pass, $password);
        if($t_pass == false){
            $pass .= '  +'.($i+1).' - '.$password.'<br> ';
            $ins_stud_test = insert_student_test($id_student, $password, $id_vpz);
        }else{
            $i=$i-1;
        }
    }
    $a_text = "Добавлена попытка. id: $id_student , кол-во попыток: $kol_try";
    insert_action_log($id_vpz, $a_text, '2');

    $res_email = get_email($id_student);
    $email = $res_email[0]['email'];
    if($email != 'no'){
        $res = send_email($email, $pass, $text);
    }else{
        $res = 'no_email';
    }

    echo json_encode(array("status"=>"ok", "text" => "ok".$res));
}

function update_user($id, $fam, $name, $fath, $email){

    $fam = str_replace(" ","",$fam);

    $name = str_replace(" ","",$name);

    $fath = str_replace(" ","",$fath);
	
	if (!$fam or $fam == '') {
        return json_encode(array("status" => "error", "text" => "не указана фамилия"));
        exit();
    } else {
        $fam = $fam;
    }

    if (!$fath or $fath == '') $fath = ' '; else $fath = $fath;

    if($name == ''){
        return json_encode(array("status"=>"error","text"=>"не указано ИМЯ"));
        exit();
    }else{
        $name = $name;
    }

    $email = str_replace(" ","",$email);

    if(($email == '') or ($email == 'no')){
        $email = 'no';
    }else{
        $test_email_query = $email.' and not id = '.$id;
            if(email_format($email) == true){
                $email = strtolower($email);
            }else{
                return json_encode(array("status"=>"error", "text" => "email введен неверно"));
                exit();
            }
    }
    $pass = "Пароли на вход:<br>";
    $text = "Для прохождения тестирования пройдите на сайт <a href='https://prob-voudso.testcenter.kz/'>https://prob-voudso.testcenter.kz</a><br>Для входа используйте свой ИИН.<br>";
    $res_pass = show_pass($id);
    $i = 0;
    foreach($res_pass as $item):
        $i++;
        $pass .= $i.' - '.$item['test_pass'].'<br>';
    endforeach;

    if($email != 'no'){
        $res = send_email($email, $pass, $text);
    }else{
        $res = 'no_email';
    }

    $upd_user = update_user_db($id, $fam , $name, $fath, $email);
    if($upd_user == true){
        return json_encode(array("status"=>"ok"));
        exit();
    }else{
        return json_encode(array("status"=>"error"));
        exit();
    }
}

function test_kol_try($kol_try){
    if(!$kol_try or $kol_try == '' or $kol_try < 1 or $kol_try > 100){
        return "неверно введено количество попыток!!!";
    }else{
        return false;
    }
}

function test_gen_id_iin($text){
    include_once  "./././connect.php";
    db_connect();
    $query = "select max(substr(iin, 9, 4)) from student where substr(iin, 1, 1) = '1' and substr(iin, 2, 7) = '$text'";
    $result = pg_query($query);
    $result = pg_fetch_array($result);
    return $result['max'];
}

function test_gen_iin($gen_iin){
    include_once  "./././connect.php";
    db_connect();
    $query = "select iin from student where iin = '$gen_iin'";
    $result = pg_query($query);
    $result = pg_num_rows($result);
    if($result > 0){
        return false;
    }else{
        return true;
    }
}

function getUser($iin)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "SELECT id from student where iin = '$iin';";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function get_stud_test($id_student, $id_vpz)//кол-во
{
    include_once  "./././connect.php";
    db_connect();
    $query = "select id from student_test where id_student = $id_student and id_user = $id_vpz;";
    $result = pg_query($query);
    $result = pg_num_rows($result);
    return $result;
}

function valid_nn($nn){//проверка иин
    $s = 0;
    for ($i = 0; $i < 11; $i++){
        $s = $s + ($i + 1) * $nn[$i];
    }
    $k = $s % 11;
    if ($k == 10){
        $s = 0;
        for ($i = 0; $i < 11; $i++){
            $t = ($i + 3) % 11;
            if($t == 0) $t = 11;
            $s = $s + $t * $nn[$i];
        }
        $k = $s % 11;
        if ($k == 10) return false;
    }
    return ($k == substr($nn,11,1));
}

function get_test_email($email)//вывод id_obl, id_raion, id_uch_zav
{
    include_once  "./././connect.php";
    db_connect();
    $query = "select u.email from public.student u where u.email = '".$email."' ;";
    $result = pg_query($query);
    $res = pg_num_rows($result);
    return $res;
}

function email_format($email) { //проверка email
    if(preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $email) !== 0) {
        if(strlen($email) < 6) return FALSE; else return TRUE;
    } else { return FALSE; }
}

function insert_user($lastname, $firstname, $patronymic, $iin, $email)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "insert into student ( lastname , firstname, patronymic, iin, email) values
				('$lastname' , '$firstname', '$patronymic', '$iin', '$email');";
    $result = pg_query($query);
    return $result;
}

function password($n){
    $chars="1234567890";
    $max=$n;
    $size=StrLen($chars)-1;
    $password=null;
    while($max--){
        $password.=$chars[rand(0,$size)];
    }
    return $password;
}

function insert_student_test($id_student , $password, $id_user)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "insert into student_test ( id_student, test_pass, id_user) values (
              $id_student , '$password', $id_user);";
    $result = pg_query($query);
    return $result;
}

function insert_action_log($id_vpz, $text, $a_type)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "insert into action_log ( id_user_role, id_user, descr, action_type) values
				(2 , $id_vpz, '$text', $a_type);";
    $result = pg_query($query);
    return $result;
}

function show_pass($id_student)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "select test_pass from student_test
			  where id_student = $id_student and not id_test_status = 3
			order by id";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function get_email($id_student)//email
{
    include_once  "./././connect.php";
    db_connect();
    $query = "SELECT email FROM student where id = $id_student";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function update_user_db($id, $fam , $name, $fath, $email)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "update student set firstname = '$name', lastname = '$fam', patronymic = '$fath', email = '$email'
				where id = $id";
    $result = pg_query($query);
    return $result;
}
function send_email($email, $password, $text){ //отправка почты

    include_once "./././SendMailSmtpClass.php"; 

    $mailSMTP = new SendMailSmtpClass('probnoe@ncgsot.kz', '123456', 'mail.ncgsot.kz', 'NCT' ); // создаем экземпл¤р класса

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
    $headers .= "From: <probnoe@ncgsot.kz>\r\n"; // от кого письмо !!! тут e-mail, через который происходит авторизация
    $result =  $mailSMTP->send($email, 'NCT', $text.$password, $headers); // отправляем письмо

    //if ($result === true){
    //	$res = 'true'; 
    //}else{
    //	$res = 'false';
    //}
    return $result;
}