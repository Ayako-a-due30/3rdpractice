<?php

require('../function/function.php');

session_start();
$dbh = dbConnect();
if(!empty($_POST)){
    $username = $_POST['username'];
    $age = $_POST['age'];
    $tel = $_POST['tel'];
    $zip = $_POST['zip'];
    $addr = $_POST['addr'];

    // 入力チェック
    validRequired($username,'username');
    validRequired($age,'age');
    validRequired($tel,'tel');
    validRequired($zip,'zip');
    validRequired($addr,'addr');

    if(empty($err_msg)){
        //半角チェック
        validHalf($age,'age');
        validHalf($tel,'tel');
        //電話番号形式
        validTel($tel,'tel');
        //郵便番号形式
        validZip($zip,'zip');

        if(empty($err_msg)){
            try{
                $dbh= dbConnect();
                $sql = 'UPDATE users SET username =:username,age=:age,tel=:tel,zip=:zip,addr=:addr WHERE id =:u_id';
                $data = array(':username'=>$username,':age'=>$age,':tel'=>$tel,':zip'=>$zip,':addr'=>$addr,':u_id'=>$_SESSION['user_id']);
                    $stmt= queryPost($dbh,$sql,$data);
                    print_r($dbh);
                    if($stmt){
                        $_SESSION['success']=SUC01;
                        header("Location:mypage3.php");
                    }
            }catch(Exception $e){
                $err_msg['common']= ERR11;
            }
            // $dsn = 'mysql:dbname=freamarket;host=localhost;charset=utf8';
            // $user = 'root';
            // $password='root';
            // $options = array(
            //     PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            //     PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            //     PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,
            // );
            // $dbh = new PDO($dsn,$user,$password,$options);
            // $stmt = $dbh->prepare('UPDATE users SET username =:username,age=:age,tel=:tel,zip=:zip,addr=:addr WHERE id =:u_id');
            // $stmt->execute(array(':username'=>$username,':age'=>$age,':tel'=>$tel,':zip'=>$zip,':addr'=>$addr,':u_id'=>$_SESSION['user_id']));
        }


    }

}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール編集</title>
</head>
<body>
    <h2>プロフィール編集画面</h2>
    <form action="" method="post">
            <?php if (!empty($err_msg['common'])) echo $err_msg['common'];?>
            <?php if(!empty($err_msg['username'])) echo $err_msg['username'];?>
        お名前*<input type="text" name="username"><br>
            <?php if(!empty($err_msg['age'])) echo $err_msg['age'];?>
        年齢*<input type="text" name="age"><br>
            <?php if(!empty($err_msg['tel'])) echo $err_msg['tel']; ?>
        電話番号(ハイフンなし)*<input type="tel" name="tel"><br>
            <?php if(!empty($err_msg['zip'])) echo $err_msg['zip'];?>
        郵便番号*<input type="text" name="zip"><br>
            <?php if(!empty($err_msg['addr'])) echo $err_msg['addr'];?>
        住所*<input type="text" name="addr"><br>
        <input type="submit" value="更新する"><br>
    </form>
    <a href="mypage3.php">マイページへ</a>
</body>
</html>