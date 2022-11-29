<?php

require('../function/function.php');

if(empty($_SESSION['auth_key'])){//auth_keyとPOST送信があるかどうかで分岐させる考え方で合ってる？
    header("Location:login.php");
}

if(!empty($_POST['newPass'])){
    $newPass = $_POST['newPass'];
    $newPassRe = $_POST['newPassRe'];

    //未入力
    validRequired($newPass,'newPass');
    validRequired($newPassRe,'newPassRe');

    //6文字以上255文字以内、半角
    validPass($newPass,'newPass');

    //同値チェック
    validMatch($newPass,$newPassRe,'newPass');

    validPass($newPass,'newPass');

    //パスワードの書き換え
    if(empty($err_msg)){
        try{
            $dbh = dbConnect();
            $sql = 'UPDATE users SET password = :password WHERE id = :id';
            $data = array(':password'=>password_hash($newPass,PASSWORD_DEFAULT),':id' =>$_SESSION['user_id']);
            $stmt = queryPost($dbh,$sql,$data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt){
                $_SESSION['message']= SUC02;
                header("Location:mypage3.php");
            }
        }catch(Exception $e){
            $err_msg['newPass']= ERR04;
        }
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新パスワード設定</title>
</head>
<body>
    <h1>新しいパスワード設定</h1>
    <form action="" method="post">
        <?php if(!empty($err_msg['newPass'])) echo $err_msg['newPass'];?>
        新しいパスワード<input type="password" name="newPass">
        <?php if(!empty($err_msg['newPassRe'])) echo $err_msg['newPassRe'];?>
        新しいパスワード（再入力）<input type="password" name="newPassRe">
        <input type="submit" value="送信">
    </form>
</body>
</html>