<?php
function vpz_table($id){
    include_once  "./././connect.php";
    session_start();
    $id_vpz = $_SESSION['loginID'];
    if(isset($id_vpz)){
        $school = ($id == 0)?' ':' and o.id = '.$id;
        db_connect();
        $query = "select s.id, s.iin, s.firstname, s.lastname, s.patronymic, s.email, 
                     case when t1.cnt is null then 0 else t1.cnt end as cnt1,
                     case when t2.cnt is null then 0 else t2.cnt end as cnt2
                from student s
                left join (select id_student, count(*) as cnt from student_test
                            where id_user = $id_vpz and not id_test_status = 0
                            group by id_student) t1 on t1.id_student = s.id

                left join (select id_student, count(*) as cnt from student_test
                            where id_user = $id_vpz and id_test_status = 0
                            group by id_student) t2 on t2.id_student = s.id
                inner join student_test t on t.id_student = s.id
                where t.id_user = $id_vpz $school
                group by  s.id, s.iin, s.firstname, s.lastname, s.patronymic, t1.cnt, t2.cnt
                order by s.id";
        $res = pg_query($query);
		if(pg_num_rows($res) > 0){
			$res = db_result_to_array($res);
			foreach($res as $item){
				$data = array(
					'id' => $item['id'], 'iin' =>  $item['iin'], 'firstname' =>  $item['firstname'],
					'lastname' => $item['lastname'], 'patronymic' => $item['patronymic'], 'email' => $item['email'],
					'cnt1' => $item['cnt1'], 'cnt2' => $item['cnt2'],
					'print' => "<button class=\"btn btn-info\" onclick=\"print_user(".$item['id'].");\">печать</button>",
					'add' => "<button class=\"btn btn-success\" onclick=\"show_add_try(".$item['id'].");\">добавить</button>",
					'update' => " <button class=\"btn btn-primary\" onclick=\"show_update_user(".$item['id'].");\">правка</button>"
				);
				$data1[] = $data;
			}
		}else{
		$data1 = array(
					'id' => 0, 'iin' =>  0, 'firstname' =>  0,
					'lastname' => 0, 'patronymic' => 0, 'email' => 0,
					'school' => 0, 'cnt1' => 0, 'cnt2' => 0,
					'print' => " ",
					'add' => " ",
					'update' => " "
				);
		}
        $result = $data1;

        return $result;
    }else{
        return false;
    }

}

function vpz_table_group($id){
    include_once  "./././connect.php";
    session_start();
    $id_vpz = $_SESSION['loginID'];
    if(isset($id_vpz)){
        $school = ($id == 0)?' ':' and o.id = '.$id;
        db_connect();
        $query = "select s.id, s.login, s.username, s.password, o.name_rus,
                    case when s1.cnt is null then 0 else s1.cnt end as cnt,
                    r.count_try
                    from users s
                        left join user_restrict r on r.id_user = s.id
                        left join test_org o on o.id = r.id_test_org
                        left join (select st.id_user, count(*) as cnt from student_test st
                                    group by st.id_user) s1 on s1.id_user = s.id
                    where r.id_user_ppent = $id_vpz
                    order by s.id";
        $res = pg_query($query);
        if(pg_num_rows($res) < 1){
            $data = array(
                'id' => 0, 'login' =>  0, 'fio' =>  0,
                'password' => 0, 'school' => 0,
                'cnt1' => 0, 'cnt2' => 0,
                'print' => "<button class=\"btn btn-info\" onclick=\"print_otvetsec(0);\">печать</button>",
                'add' => "<button class=\"btn btn-success\" onclick=\"show_add_try_otvetsec(0);\">добавить</button>",
                'update' => " <button class=\"btn btn-primary\" onclick=\"show_update_otvetsec(0);\">правка</button>"
            );
            $data1 = $data;
        }else{
		$res = db_result_to_array($res);
            foreach($res as $item){
                $c = ($item['count_try']-$item['cnt']);
                $data = array(
                    'id' => $item['id'], 'login' =>  $item['login'], 'fio' =>  $item['username'],
                    'password' => $item['password'], 'school' => $item['name_rus'],
                    'cnt2' => $item['cnt'], 'cnt1' => $c,
                    'print' => "<button class=\"btn btn-info\" onclick=\"print_otvetsec(".$item['id'].");\">печать</button>",
                    'add' => "<button class=\"btn btn-success\" onclick=\"show_add_try_otvetsec(".$item['id'].");\">добавить</button>",
                    'update' => " <button class=\"btn btn-primary\" onclick=\"show_update_otvetsec(".$item['id'].");\">правка</button>"
                );
                $data1[] = $data;
            }
        }

        $result = $data1;

        return $result;
    }else{
        return false;
    }

}