<?php
include_once  "../../connect.php";
session_start();
    $id = $_POST['id'];
    $res = show_user($_SESSION['vpz'], $id);

foreach($res as $item){

    $data = array(
        'id' => $item['id'], 'iin' =>  $item['iin'], 'firstname' =>  $item['firstname'],
        'lastname' => $item['lastname'], 'patronymic' => $item['patronymic'],
        'school' => $item['name_rus'], 'cnt1' => $item['cnt1'], 'cnt2' => $item['cnt2'],
        'print' => "<button class=\"btn btn-info\" onclick=\"print_user(".$item['id'].");\">печать</button>",
        'add' => "<button class=\"btn btn-success\" onclick=\"show_add_try(".$item['id'].");\">добавить</button>",
        'update' => " <button class=\"btn btn-primary\" onclick=\"show_update_user(".$item['id'].");\">правка</button>"
    );
    $data1[] = $data;
}

$result = array('users'=>$data1);

echo json_encode($result);
