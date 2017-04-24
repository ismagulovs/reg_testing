<?php 
//session_start();
//include('../connect.php');
//include("../mpdf/mpdf.php");
//if(stripos($_GET['id'], '|') == true){
//	$users = explode('|', $_GET['id']);
//	$html ='';
//	for($i=0;$i<count($users);$i++){
//		$id_student = $users[$i];
//		$res = get_stud_print($id_student, $_SESSION['vpz']);
//		$html .= '
//		<table border="1">
//			<tr>
//				<td><b>ППЗ</b></td>
//				<td>'.$res[0]['username'].'</td>
//			</tr>
//			<tr>
//				<td><b>иин</b></td>
//				<td>'.$res[0]['iin'].'</td>
//			</tr>
//			<tr>
//				<td><b>ФИО</b></td>
//				<td> '.$res[0]['lastname'].' '.$res[0]['firstname'].' '.$res[0]['patronymic'].'</td>
//			</tr>
//			<tr>
//				<td><b>email</b></td>
//				<td>'.$res[0]['email'].'</td>
//			</tr>
//			<tr>
//				<td><b>адрес</b></td>
//				<td>http://prob-pedrab.testcenter.kz/</td>
//			</tr>
//			<tr>
//				<td><b>кол-во попыток сдачи:</b></td>
//				<td>'.$res[0]['cnt'].'</td>
//			</tr>
//			<tr>
//			<tr> <td colspan="2" align="center"><b>пароли</b></td>  </tr>';
//			  foreach($res as $item):
//				$html  .='<tr><td colspan="2" align="center">'.$item['test_pass'].'</td></tr>';
//			endforeach;
//		$html  .= ' </table>';
//	}
//
//}else{
//	 $id_student = $_GET['id'];
//	 $res = get_stud_print($id_student, $_SESSION['vpz']);
//	$html = '
//	<table border="1">
//		<tr>
//			<td>ВУЗ</td>
//			<td>'.$res[0]['username'].$text.'</td>
//		</tr>
//		<tr>
//			<td>иин</td>
//			<td>'.$res[0]['iin'].'</td>
//		</tr>
//		<tr>
//			<td>ФИО</td>
//			<td> '.$res[0]['lastname'].' '.$res[0]['firstname'].' '.$res[0]['patronymic'].'</td>
//		</tr>
//		<tr>
//			<td>email</td>
//			<td>'.$res[0]['email'].'</td>
//		</tr>
//		<tr>
//			<td>адрес</td>
//			<td>http://prob-pedrab.testcenter.kz/</td>
//		</tr>
//		<tr>
//			<td>кол-во попыток сдачи:</td>
//			<td>'.$res[0]['cnt'].'</td>
//		</tr>
//		<tr>
//		<tr> <td colspan="2" align="center">пароли</td>  </tr>';
//		  foreach($res as $item):
//			$html  .='<tr><td colspan="2" align="center">'.$item['test_pass'].'</td></tr>';
//		endforeach;
//	$html  .= ' </table>';
//}
//
////Кодировка | Формат | Размер шрифта | Шрифт
////Отступы: слева | справа | сверху | снизу | шапка | подвал
//$mpdf = new mPDF('utf-8', 'A4', '10', 'Arial', 0, 0, 5, 5, 5, 5);
//$mpdf->charset_in = 'utf-8';
//
//$stylesheet = 'table {
//                    text-align: center;
//                    widtd: 500px;
//                    color: #000;
//                    margin: 0 auto;
//                    margin-bottom: 20px;
//               }
//               td {
//					text-align: left;
//               }';
//
////Записываем стили
//$mpdf->WriteHTML($stylesheet, 1);
//$mpdf->list_indent_first_level = 0;
////Записываем html
//$mpdf->WriteHTML($html, 2);
//$mpdf->Output('mpdf.pdf', 'I');
//$id_vpz = vpz();
//if($id_vpz != false){
//    function($id){
//
//    }
//}
//

function print_stud_pass($id_student){
    $id_vpz = vpz();
    $res = get_stud_print($id_student, $id_vpz);
    $html = '
    <html>
    <head>
    <style>
        table {
             text-align: center;
             widtd: 500px;
             color: #000;
             margin: 0 auto;
             margin-bottom: 20px;
        }
        td {
                text-align: left;
        }
    </style>
    </head>
    <body>
        <table border="1">
            <tr>
                <td>ВУЗ</td>
                <td>'.$res[0]['username'].'</td>
            </tr>
            <tr>
                <td>иин</td>
                <td>'.$res[0]['iin'].'</td>
            </tr>
            <tr>
                <td>ФИО</td>
                <td> '.$res[0]['lastname'].' '.$res[0]['firstname'].' '.$res[0]['patronymic'].'</td>
            </tr>
            <tr>
                <td>email</td>
                <td>'.$res[0]['email'].'</td>
            </tr>
            <tr>
                <td>адрес</td>
                <td>https://prob-voudso.testcenter.kz</td>
            </tr>
            <tr>
                <td>кол-во попыток сдачи:</td>
                <td>'.$res[0]['cnt'].'</td>
            </tr>
            <tr>
            <tr> <td colspan="2" align="center">пароли</td>  </tr>';
              foreach($res as $item):
                $html  .='<tr><td colspan="2" align="center">'.$item['test_pass'].'</td></tr>';
            endforeach;
        $html  .= ' </table></body></html>';
    return $html;
}
function print_stvetsec_pass($id){
    $id_vpz = vpz();
    $res = get_otvetsec_print($id, $id_vpz);
    $html = '
    <html>
    <head>
    <style>
        table {
             text-align: left;
             widtd: 500px;
             color: #000;
             margin: 0 auto;
             margin-bottom: 20px;
        }
        td {
                text-align: left;
        }
    </style>
    </head>
    <body>
        <table border="1">
            <tr>
                <td>Школа</td>
                <td>'.$res[0]['name_rus'].'</td>
            </tr>
            <tr>
                <td>Логин</td>
                <td>'.$res[0]['login'].'</td>
            </tr>
            <tr>
                <td>ФИО</td>
                <td> '.$res[0]['username'].' </td>
            </tr>
            <tr>
                <td>кол-во</td>
                <td>'.$res[0]['count_try'].'</td>
            </tr>
			<tr>
                <td>пароль</td>
                <td>'.$res[0]['password'].'</td>
            </tr>
            <tr>
                <td>адрес</td>
                <td>https://prob-voudso.testcenter.kz</td>
            </tr></table></body></html>';
    return $html; 
}

function vpz(){
    session_start();
    if($_SESSION['loginID']) return $_SESSION['loginID']; else return false;
}

function get_stud_print($id_student, $id_vpz)//вывод id_obl, id_raion, id_uch_zav
{
    include(__DIR__ .'/../connect.php');
    db_connect();
    $query = "select s.iin, s.firstname, s.lastname, s.patronymic, s.email, t.test_pass, t1.cnt as cnt,
                    o.username, t2.name_kaz, t2.name_rus
                    from student s
                        inner join student_test t on t.id_student = s.id
                        inner join users o on o.id = t.id_user
                        left join (select id_student, id_user, count(*) as cnt from student_test
                         group by id_student, id_user) t1
                          on t1.id_student = s.id
                          and t1.id_user = t.id_user
                        left join test_org t2 on t2.id = s.id_test_org
                    where t.id_user = $id_vpz and s.id = $id_student and t.id_test_status = 0
                    order by t.id";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}

function get_otvetsec_print($id, $id_vpz)//вывод 
{
    include(__DIR__ .'/../connect.php');
    db_connect();
    $query = "select s.username, s.login, s.password, r.count_try, o.name_rus
			from users s 
				inner join user_restrict r on r.id_user = s.id
				inner join test_org  o on o.id = r.id_test_org 
			where s.id = $id and r.id_user_ppent = $id_vpz";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}