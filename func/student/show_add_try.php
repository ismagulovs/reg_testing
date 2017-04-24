<?php
function show_add_try($id){
    include_once  "./././connect.php";

    $id_vpz = vpz_id();
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
    $res = db_result_to_array($result);

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
    return json_encode(array("status"=>"ok","htm"=>$htm));
}

function vpz_id(){
    session_start();
    if(isset($_SESSION['loginID'])){
        return $_SESSION['loginID'];
    }else{
        return false;
    }
}
