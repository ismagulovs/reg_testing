<?php
date_default_timezone_set('Asia/Almaty');
if(isset($_POST['type'])){
    switch($_POST['type']){
        case 'login':
            include('func/login.php');
            $res = login($_POST['login'], $_POST['password']);
            if($res == true)echo json_encode(array("status"=> "login")); else echo json_encode(array("status" => "error", "text" => "неверный логин или нароль"));
            break;
        case 'vpz_table':
            include('func/vpz/table.php');
            $res_table = vpz_table($_POST['id']);
            $res_vpz = include('func/vpz/vpz.php');;
            if($res_table == false or $res_vpz == false)echo json_encode(array("status"=> $res_table)); else echo json_encode(array("users" => $res_table, "vpz" => $res_vpz));
            break;
        case 'add_individual_student':
            include('func/student/add.php');
            $res = add_student($_POST['iin'], $_POST['fam'], $_POST['name'], $_POST['fath'], $_POST['email'], $_POST['kol_try'], $_POST['iinchb']);
            echo $res;
            break;
        case 'show_add_try':
            include('func/student/show_add_try.php');
            $res = show_add_try($_POST['id']);
            echo $res;
            break;
        case 'add_try':
            include('func/student/add.php');
            $res = add_try($_POST['id'], $_POST['kol_try']);
            echo $res;
            break;
        case 'show_update_user':
            include('func/student/show_update_user.php');
            $res = show_update_user($_POST['id']);
            echo $res;
            break;
        case 'update_user':
            include('func/student/add.php');
            $res = update_user($_POST['id'], $_POST['fam'], $_POST['name'], $_POST['fath'], $_POST['email']);
            echo $res;
            break;
        case 'print':
            include('func/print.php');
            $res = print_stud_pass($_POST['id']);
            echo json_encode(array("print"=> $res));
            break;
			
			

        ///group
        case 'vpz_table_group':
             include('func/vpz/table.php');
             $res_table = vpz_table_group($_POST['id']);
             $res_vpz = include('func/vpz/vpzGroup.php');
             if($res_table == false or $res_vpz == false)echo json_encode(array("status" => $res_table)); else echo json_encode(array("otvetsec" => $res_table, "vpzGroup" => $res_vpz));
        break;
        case 'add_otvetsec':
             include('func/group/add_otvetsec.php');
             $res = add_otvetsec($_POST['login'], $_POST['fio'],$_POST['school'], $_POST['kol_try']);
             echo $res;
        break;
        case 'show_add_try_otvetsec':
             include('func/group/show_add_try_otvetsec.php');
             $res = show_add_try_otvetsec($_POST['id']);
             echo $res;
        break;
        case 'add_try_otvetsec':
            include('func/group/add_otvetsec.php');
            $res = add_try_otvetsec($_POST['id'], $_POST['kol_try']);
            echo $res;
        break;
        case 'show_update_otvetsec':
             include('func/group/show_update_otvetsec.php');
             $res = show_update_otvetsec($_POST['id']);
             echo $res;
        break;
        case 'update_otvetsec':
            include('func/group/add_otvetsec.php');
            $res = update_otvetsec($_POST['id'], $_POST['fio'], $_POST['school']);
            echo $res;
        break;
        case 'add_school':
            include('func/addSchool.php');
            $res = add_school($_POST['obl'], $_POST['raion'], $_POST['name_kaz'], $_POST['name_rus']);
            echo  $res;
        break;
		case 'print_otvsec':
            include('func/print.php');
            $res = print_stvetsec_pass($_POST['id']);
            echo json_encode(array("print"=> $res));
        break;
    }
}

if(isset($_POST['route'])){
    switch($_POST['route']){
        case 'individual':
            setcookie("vpz", "individual");
        break;
        case 'group':
            setcookie("vpz", "group");
        break;
        case 'exit':
            include('func/route.php');
            $res = exit_proj();
            echo json_encode(array("status"=> "ok"));
        break;
    }
}
