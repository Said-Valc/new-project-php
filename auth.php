<?php
    require_once "config.php";

  

if(isset($_POST['clickAuth'])){
    auth();
    redirect();
}

function auth(){
    $login = strip_tags(trim($_POST['login']));
    $password = strip_tags(trim($_POST['password']));
    if($login == LOGIN && $password == PASSWORD){
        $_SESSION['auth'] = true;
    }else{
        $_SESSION['error'] = 'Авторизация провалена';
    }
   
}

function redirect(){
    header('Location: index.php');
}