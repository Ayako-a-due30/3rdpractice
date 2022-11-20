<?php
require('../function/function.php');

if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $stmt = '';
    
    //バリデーション
    //未入力
    validRequired($email,'email');
    validRequired($pass,'pass');

    if(empty($err_msg)){
        try{
            $dbh=dbConnect();
            $sql = 'SELECT password,id FROM users WHERE email= :email AND delete_flg = 0';
            $data = array(':email'=>$email);
            $stmt=queryPost($dbh,$sql,$data);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            //パスワード照合
            if(!empty($result) && password_verify($pass,array_shift($result))){
                $sesLimit = 60*60;
                $_SESSION['login_date']=time();
                $_SESSION['login_limit'] = $sesLimit;
                $_SESSION['user_id'] = $result['id'];
                header("Location:mypage3.php");
            }else{
                $err_msg['pass'] = ERR12; 
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
        <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?><br>
        <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?><br>
        メールアドレス<input type="email"  name="email"><br>
        <?php if (!empty($err_msg['pass'])) echo $err_msg['pass'];?><br>
        パスワード<input type="password" name="pass"><br>
        <input type="submit" value="ログインする"><br>
        <a href="signUp.php">新規登録画面へ</a><br>
        <a href="passKeySend.php">パスワードをお忘れの方</a>
    </form>
</body>
</html>