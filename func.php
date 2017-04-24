<?php
include('connect.php'); 
if (function_exists('date_default_timezone_set')) date_default_timezone_set('Asia/Almaty');
session_start();
if($_POST['type'] == 'login')//авторизация ппент
{
	if(isset($_POST['login']) and isset($_POST['pass']) and $_POST['login'] != '' and $_POST['pass'] != ''){
		$res = get_vpz($_POST['login']);
		if($res[0]['password'] == $_POST['pass']){
			$_SESSION['vpz'] = $res[0]['id'];
			echo json_encode(array("status"=> "login"));
		}else {
            echo json_encode(array("status" => "error", "text" => "неверный логин или нароль"));
        }
	}else{
		echo json_encode(array("status"=>"error", "text" => "неверные данные"));
	}
}else if($_POST['type'] == 'iuser'){//добавление пользователя -------------------------------------------

    if($_POST['iinchb'] === 'true'){//ut\\генерация ИИНа -\\\\\\\\\\\\\\\\\\\\\\\\------------------------

        $nppz = sprintf("%03d", $_SESSION['vpz']);

        $ndate = date('ym');

        $max_id = test_gen_id_iin($nppz.$ndate);
        if($max_id == null) $max_id = 0;
        do{
            $max_id = $max_id + 1;
            $gen_iin = '1'.$nppz.$ndate.sprintf("%04d", $max_id);
            $test_geniin = test_gen_iin($gen_iin);
        }while($test_geniin === false);
        $iin = $gen_iin;

    }else{//---------проверка ИИНа -////////////////////////====----------------------------
        $iin = preg_replace('~ +~', ' ', $_POST['iin']);
        $res = getUser($iin);
        $massage = '';
        if(count($res) > 0){ //если студент с таким иином уже есть...
            $id_student = $res[0]['id'];
            $res_stud_test = get_stud_test($id_student, $_SESSION['vpz']);//првоеряем есть ли иин в ппент
            if($res_stud_test > 0){
                echo json_encode(array("status"=>"error", "text" => "пользователь с таким ИИН уже зарегестрирован у вас в таблице"));
                exit();
            }
            $massage = "пользователь уже был зарегестрирован в другом ППЕНТ";
        }else{
            $nn = valid_nn($iin);
            if($nn == false || $iin == null  || $iin == ' ' || is_numeric($iin) == false || $iin == 0 || strlen($iin) != 12){
                echo json_encode(array("status"=>"error","text" => "неверный ИИН"));
                exit;
            }
        }
    }

    if(($test_kol_try = test_kol_try($_POST['kol_try'])) === false){
        $kol_try = $_POST['kol_try'];
    }else{
        echo json_encode(array("status"=>"error","text"=>$test_kol_try));
        exit();
    }


    if($massage != "пользователь уже был зарегестрирован в другом ППЕНТ"){
        //------------проверка заполнения данных------------------
        if(!$_POST['fam'] or $_POST['fam'] == ''){
            echo json_encode(array("status"=>"error","text"=>"не указана фамилия"));
            exit();
        }else{
            $fam = $_POST['fam'];
        }

        if(!$_POST['name'] or $_POST['name'] == ''){
            echo json_encode(array("status"=>"error","text"=>"не указано ИМЯ"));
            exit();
        }else{
            $name = $_POST['name'];
        }

        if(!$_POST['fath'] or $_POST['fath'] == '') $fath = ' '; else $fath = $_POST['fath'];



        $email = str_replace(" ","",$_POST['email']);
        if(!$email or $email == ''){
            $email = 'no';
        }else{
            if(get_test_email($_POST['email']) > 0){
                echo json_encode(array("status"=>"error","text" => "email уже зарегистрирован в базе"));
                exit();
            }else{
                if(email_format($_POST['email'] ) == true){
                    $email = strtolower($_POST['email']);
                }else{
                    echo json_encode(array("status"=>"error", "text" => "email введен неверно"));
                    exit();
                }
            }
        }

		if(!$_POST['school'] || $_POST['school'] == '' || $_POST['school'] == '0'){
            echo json_encode(array("status"=>"error","text"=>"не указана школа"));
            exit();
        }else{
            $school = $_POST['school'];
        }

        $ins_user = insert_user($fam , $name, $fath, $iin, $email, $school);
        if($ins_user == false){
            echo json_encode(array("status"=>"error", "text"=>"ошибка звписи в базу!!! обратитсь к программистам"));
            exit();
        }
        $res = getUser($iin);
        $id_student = $res[0]['id'];
        $massage = "пользователь добавлен";
    }

    ///добавление попыток в student test----------------------------
    $pass = "Пароли на вход:<br> ";
    $text = "Для прохождения тестирования зайдите на сайт <a href='http://prob-pedrab.testcenter.kz'>http://prob-pedrab.testcenter.kz</a><br>Для входа используйте свой ИИН: ".$iin."<br>";

    for($i = 0; $i < $kol_try; $i++){
        $password = password(8);
		$t_pass = strpos($pass, $password);
		if($t_pass == false){
			$i++;//нужно!!!!
			$pass .= $i.'. '.$password.'<br>';
			$i--;
			$ins_stud_test = insert_student_test($id_student, $password, $_SESSION['vpz']);
		}else{
			$i = $i-1;
		}
    }
	
		$a_text = "Добавлен тестируемый id: $id_student , кол-во попыток: $kol_try";
		insert_action_log($_SESSION['vpz'], $a_text, '1');

    if(isset($email)){
        if($email != 'no'){
            $res_mail =  send_email($email, $pass, $text);
        }else{
            $res_mail = 'no_mail';
        }
    }

    echo json_encode(array("status"=>"ok", "id" => $id_student, "text" => $massage, 'email' => $res_mail));
    exit();


}else if($_POST['type'] == 'add_try'){//добавление попытки---------------------------

    if(!$_POST['kol_try'] or $_POST['kol_try'] == '' or $_POST['kol_try'] < 1 or $_POST['kol_try'] > 100){
        echo json_encode(array("status"=>"error","text"=>"неверно введено количество попыток"));
        exit();
    }else{
        $kol_try = $_POST['kol_try'];
    }
    $id_student = $_POST['id'];
    $pass = "Пароли на вход:<br>";
    $text = "Для прохождения тестирования пройдите на сайт <a href='http://prob-pedrab.testcenter.kz'>http://prob-pedrab.testcenter.kz</a><br>Для входа используйте свой ИИН.<br>";
	
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
			$ins_stud_test = insert_student_test($id_student, $password, $_SESSION['vpz']);
		}else{
			$i=$i-1;
		}     
    }
		$a_text = "Добавлена попытка. id: $id_student , кол-во попыток: $kol_try";
		insert_action_log($_SESSION['vpz'], $a_text, '2');
		
    $res_email = get_email($id_student);
    $email = $res_email[0]['email'];
    if($email != 'no'){
       $res = send_email($email, $pass, $text);
    }else{
        $res = 'no_email';
    }

	echo json_encode(array("status"=>"ok", "text" => $res));

}else if($_POST['type'] == 'show_add_try'){//вывод ммодал для добавления попытки

    $res = show_add_try($_SESSION['vpz'], $_POST['id']);
    $htm = '';
    $htm .= '<div class="form-group">
				<h3>ИИН: '.$res[0]['iin'].'</h3> 
				<h3>Фамилия: '.$res[0]['lastname'].'</h3>
				<h3>Имя: '.$res[0]['firstname'].'</h3>
				<h3>Отчество: '.$res[0]['patronymic'].'</h3>
				<h3>email: '.$res[0]['email'].'</h3>
				    <div class="row">

                      <div class="col-md-1"></div>
                      <div class="col-md-1 text-right" style="font-size: 25px;">'.$res[0]['cnt'].'+</div>
                      <div class="col-md-7">
                        <input type="hidden" id="id_add_try" value="'.$res[0]['id'].'"/>
                        <input type="number" id="add_kol_try" class="form-control" placeholder="количество попыток">
                      </div>
                      <div class="col-md-5"></div>
                    </div>
			</div>';
    echo json_encode(array("status"=>"ok","htm"=>$htm));

}else if($_POST['type'] == 'show_update_user'){//вывод правки---------------------------
    $res = show_add_try($_SESSION['vpz'], $_POST['id']);
    $htm = '';
    $htm .= '<div class="form-group">
				<div>
					<label for="fam" style="margin-bottom: 1px">Фамилия</label>
					<input type="text" id="ufam" class="form-control" placeholder="Фамилия" value="'.$res[0]['lastname'].'">
				</div>
				<br>
				<div>
					<label for="name" style="margin-bottom: 1px">Имя</label>
					<input type="text" id="uname" class="form-control" placeholder="Имя" value="'.$res[0]['firstname'].'">
				</div>
				<br>
				<div>
					<label for="fath" style="margin-bottom: 1px">Отчество</label>
					<input type="text" id="ufath" class="form-control" placeholder="Отчество"  value="'.$res[0]['patronymic'].'">
				</div>
				<br>
				<div>
					<label for="email" style="margin-bottom: 1px">email</label>
					<input type="text" id="uemail" class="form-control" placeholder="email" value="'.$res[0]['email'].'">
				</div>
				  <input type="hidden" id="up_id_user" value="'.$res[0]['id'].'"/>
			</div>';
    echo json_encode(array("status"=>"ok","htm"=>$htm));

}else if($_POST['type'] == 'update_user'){//правка---------------------------

		if(!$_POST['fam']) $fam = ' '; else $fam = $_POST['fam'];

        if(!$_POST['name'] or $_POST['name'] == ''){
            echo json_encode(array("status"=>"error","text"=>"не указано ИМЯ"));
            exit();
        }else{
            $name = $_POST['name'];
        }

        if(!$_POST['fath'] or $_POST['fath'] == '') $fath = ' '; else $fath = $_POST['fath'];
		
		$email = str_replace(" ","",$_POST['email']);
        if(($email == '') or ($email == 'no')){
            $email = 'no';
        }else{
            if(get_up_test_email($_POST['email'], $_POST['id']) > 0){
                echo json_encode(array("status"=>"error","text" => "email уже зарегистрирован в базе"));
                exit();
            }else{
                if(email_format($_POST['email'] ) == true){
                    $email = strtolower($_POST['email']);
                }else{
                    echo json_encode(array("status"=>"error", "text" => "email введен неверно"));
                    exit();
                }
            }
			   
        }		
			$pass = "Пароли на вход:<br>";
			$text = "Для прохождения тестирования пройдите на сайт <a href='http://prob-pedrab.testcenter.kz'>http://prob-pedrab.testcenter.kz</a><br>Для входа используйте свой ИИН.<br>";
			$res_pass = show_pass($_POST['id']);
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
			
		$upd_user = update_user($_POST['id'], $fam , $name, $fath, $email);
		if($upd_user == true){
			echo json_encode(array("status"=>"ok"));
            exit();
		}else{
			echo json_encode(array("status"=>"error"));
            exit();
		}  
}else if($_POST['type'] == 'close'){

    unset($_SESSION['vpz']);
	
}else if($_POST['type'] == 'addSchool'){
    
	 if(!$_POST['id_obl'] or $_POST['id_obl'] == '' or $_POST['id_obl'] == '0'){
         echo json_encode(array("status"=>"error","text"=>"не выбрана область"));
         exit();
     }else{
         $id_obl = $_POST['id_obl'];
     }
	 
	 if(!$_POST['id_raion'] or $_POST['id_raion'] == '' or $_POST['id_raion'] == '0'){
         echo json_encode(array("status"=>"error","text"=>"не выбран район"));
         exit();
     }else{
         $id_raion = $_POST['id_raion'];
     }
	 
	 if(!$_POST['name_kaz'] or $_POST['name_kaz'] == ''){
         echo json_encode(array("status"=>"error","text"=>"введите наименование на казахском"));
         exit();
     }else{
         $name_kaz = $_POST['name_kaz'];
     } 
	 
	 if(!$_POST['name_rus'] or $_POST['name_rus'] == ''){
         echo json_encode(array("status"=>"error","text"=>"введите наименование на русском"));
         exit();
     }else{
         $name_rus = $_POST['name_rus'];
     }
	 
	//$res = '123';
	$ins_school = insert_school($id_obl , $id_raion , $name_kaz , $name_rus);
	if($ins_school == false){
        echo json_encode(array("status"=>"error", "text"=> "ошибка звписи в базу!!! обратитсь к программистам"));
        exit();
    }
	echo json_encode(array("status"=>"ok","text"=>"учебное заведение добавлено в базу"));
	exit();
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


function email_format($email) { //проверка email
	if(preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $email) !== 0) {
	if(strlen($email) < 6) return FALSE; else return TRUE;
	} else { return FALSE; }
}

function send_email($email, $password, $text){ //отправка почты 
	 
		include('func/SendMailSmtpClass.php');

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

function test_kol_try($kol_try){
    if(!$kol_try or $kol_try == '' or $kol_try < 1 or $kol_try > 100){
        return "неверно введено количество попыток!!!";
    }else{
        return false;
    }
}