<?php
function show_add_try_otvetsec($id){
    include_once  "./././connect.php";

    db_connect();
    $query = "select s.id, s.login, s.username, t.name_rus, r.count_try,
                sum(case when (st.id_test_status = 3) or (st.id_test_status is null) then 0 else 1 end) as cnt_try_finished
                 from users s
                 inner join user_restrict r on r.id_user = s.id
                 inner join test_org t on t.id = r.id_test_org
                 left join student_test st on st.id_user = s.id
                where s.id = $id
                group by s.id, s.login, s.username, t.name_rus, r.count_try";
    $result = pg_query($query);
    $res = db_result_to_array($result);

    $htm = '';
    $htm .= '<div class="form-group">
				<h3><b>Логин:</b> '.$res[0]['login'].'</h3>
				<h3><b>ФИО:</b> '.$res[0]['username'].'</h3>
				<h3><b>Школа:</b> '.$res[0]['name_rus'].'</h3>
				    <div class="row">
                      <div class="col-md-1"></div>
                      <div class="col-md-2 text-right" style="font-size: 25px;">'.($res[0]['count_try']-$res[0]['cnt_try_finished']).'+</div>
                      <div class="col-md-6">
                        <input type="hidden" id="id_add_try_otvetsec" value="'.$res[0]['id'].'"/>
                        <input type="number" id="add_kol_try_otvetsec" class="form-control" placeholder="количество попыток">
                      </div>
                      <div class="col-md-5"></div>
                    </div>
			</div>';
    return json_encode(array("status"=>"ok","htm"=>$htm));
}
