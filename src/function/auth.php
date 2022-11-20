<?php

if(!empty($_SESSION['login_date'])){//ログインしている
    if(($_SESSION['login_date'] + $_SESSION['login_limit'])<time()){//ログイン期限切れ
        session_destroy();
        header("Location:../app/login.php");
    }else{
        $_SESSION['login_date']=time();//期限内、最終ログイン日時を現在日時に更新
        if(basename($_SERVER['PHP_SELF'])==='login.php'){
            header("Location:../app/mypage.php");
        }
    }

}else{//ログインしてない
    if(basename($_SERVER['PHP_SELF']!=='login.php')){
        header("Location:../app/login.php");
    }
}