<?php
include('connect.php');
db_connect();
//$result = pg_query($query);
//$result1 = pg_query("insert into users (login, username, id_user_role, status, password) VALUES ('test', 'fiteo', 6, 1, 'pass')");
$id_user = pg_fetch_array(pg_query("select id from users where login = 'test'"));
if($id_user['id']>0){
    echo 'ok';
}else{
    echo'error';
}