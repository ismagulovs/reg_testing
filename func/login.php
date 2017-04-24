<?php
function login($login, $password){
    include('././connect.php');
    db_connect();
    $query = "SELECT u.id, u.password, u.username FROM users u
	            where u.login = '$login' and u.id_user_role = 2";
    $result = pg_query($query);
    $result = db_result_to_array($result);
   if($result != null){
       if(($result[0]['password'] != null) or ($result[0]['password'] == $password)){

           session_start();
           $_SESSION['loginID'] = $result[0]['id'];
           setcookie("view", "vpz");
           return true;
       }else{
           return false;
       }
   }else{
       return false;
   }

}

