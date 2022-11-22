<?php
require('../function/function.php');

if(!empty($_POST['email'])){
    $email = $_POST['email'];

    //登録済みのメールアドレスか確認する
    try{
        $dbh = dbConnect();
        $sql = 'SELECT COUNT(*)FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email'=>$email);
        $stmt=queryPost($dbh,$sql,$data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt && array_shift($result)){
            $_SESSION['success']= SUC03;
            $auth_key = makeRandKey();

            $from ='sonnige.seite.str@gmail.com';
            $to =$email;
            $subject='【パスワードキー発行】';
            $comment=<<<EOT
パスワード再発行認証キー入力ページ:http://localhost:8888/3rdpractice/src/app/passKeyInput.php
パスワードキー:{$auth_key}
キーの有効期限は３０分

EOT;
    sendMail($from,$to,$subject,$comment);
    $_SESSION['auth_key']=$auth_key;
    $_SESSION['auth_email']= $email;
    $_SESSION['auth_key_limit']=time()+60*30;
    print_r($_SESSION);
    print_r($email);
    header("Location:passKeyInput.php");
        }else{
            $err_msg['passKey']= ERR04;
        }


    }catch(Exception $e){//Exception $eって何？
        $err_msg = ERR04;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>パスコード発行</h1>
    <form action="" method ="post">
        登録済みメールアドレスを入力してください<br>
        <?php if(!empty($err_msg['passKey'])) echo $err_msg['passKey']; ?>
        <input type="email" name="email"><br>
        <input type="submit" value="登録のメールアドレスにパスコードを送信します" name="">
    </form>

</body>
</html>