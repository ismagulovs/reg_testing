<?php
function show_update_user($id){
    $id_vpz = vpz_id();
    $res = show_add_user($id_vpz, $id);
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
    return json_encode(array("status"=>"ok","htm"=>$htm));
}
function show_add_user($id_vpz, $id)
{
    include_once  "./././connect.php";
    db_connect();
    $query = "select s.id, s.iin, s.firstname, s.lastname, s.patronymic, s.email, t.cnt
                   from student s
                     inner join (select id_student, id_user, count(*) as cnt
                                from student_test
                              group by id_student, id_user) t
                            on t.id_student = s.id
                            and t.id_user = $id_vpz
                  where s.id = $id";
    $result = pg_query($query);
    $result = db_result_to_array($result);
    return $result;
}


function vpz_id(){
    session_start();
    if(isset($_SESSION['loginID'])){
        return $_SESSION['loginID'];
    }else{
        return false;
    }
}