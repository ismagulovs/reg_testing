<?php
function show_update_otvetsec($id){
    $id_vpz = vpz_id();
    $res = show_add_otvetsec($id_vpz, $id);
    $htm = '';

    $array_obl = get_obl();
    $text_select = '';

    foreach ($array_obl as $item):
        if ($item['id'] == $id_vpz){
            $selected = 'selected';
        }else{
            $selected = '';
        }
        $text_select .= '<option value="' . $item['id'] . '"  '.$selected.' >' . $item['name_rus'] . '</option>';
    endforeach;

    $htm .= '<div class="form-group">
				<div>
					<label for="fam" style="margin-bottom: 1px">Логин</label><h2>'.$res[0]['login'].'</h2>
				</div>
				<br>
				<div>
					<label for="name" style="margin-bottom: 1px">ФИО</label>
					<input type="text" id="ufio" class="form-control" placeholder="Имя" value="'.$res[0]['username'].'">
				</div>
				<br>
				<div>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                  школа
                </button>
                <div class="collapse" id="collapseExample">
                  <div class="well">
                     <div>
                        <label for="school" style="margin-bottom: 1px">учебное заведение</label>
                        <div class="row">
                            <div class="col-md-4">
                                <select  id="obl_show_update" onclick="obl_show_update();" class="form-control input-sm">'.$text_select.'
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="raion_show_update" onclick="raion_show_update();" class="form-control input-sm">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="shool_show_update" class="form-control input-sm">
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
					<h3 for="school" style="margin-bottom: 1px">'.$res[0]['name_rus'].'</h3>
				</div>
				  <input type="hidden" id="up_id_otvetsec" value="'.$res[0]['id'].'"/>
			</div>';
    return json_encode(array("status"=>"ok","htm"=>$htm));
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


function vpz_id(){
    session_start();
    if(isset($_SESSION['loginID'])){
        return $_SESSION['loginID'];
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
