<?php
function exit_proj(){
    session_start();
    unset($_SESSION['loginID']);
    session_unset();
    session_destroy();
    return true;
}