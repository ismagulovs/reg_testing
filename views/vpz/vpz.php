<?php
if(isset($_COOKIE['vpz'])) {
    switch ($_COOKIE['vpz']){
        case 'individual': include('individual/html.php');break;
        case 'group': include('group/html.php');break;
        default: include('default/html.php');break;
    }
}else{
    include('default/html.php');
}
