<?php
require('../function/function.php');

if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $result ='';
    
    //バリデーション
    //未入力
    validRequired($email,'email');
    validRequired($pass,'pass');

    if(empty($err_msg)){
        try{
            $dbh=dbConnect();
            $sql = 'SELECT*FROM users WHERE email= :email AND password=:password';
            $data = array(':email'=>$email,':password'=>$pass);
            $stmt=queryPost($dbh,$sql,$data);
            $result =$stmt->fetch(PDO::FETCH_ASSOC);
            //パスワード照合
            if(!empty($result)&& password_verify($pass,array_shift($result))){
                
            }
            if($stmt){
                // header("Location:mypage3.php");
            }
        }catch(Exception $e){
            $err_msg['common']=ERR11;
        }
    }
}

?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ログインページ</title>
    </head>
    <body>
        <h1>ログインページ</h1>
    <form action="" method="post">
        メールアドレス<input type="email"  name="email">
        パスワード<input type="password" name="pass">
        <input type="submit" value="ログインする">
        <a href="signUp.php">新規登録画面へ</a>
    </form>
</body>
</html>