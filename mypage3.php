<?php
require('function.php');
    $dbh = dbConnect();
    session_start();
    $_SESSION['user_id']=$dbh->lastInsertId();
    var_dump($_SESSION);
    print_r($_SESSION);
if(!empty($_POST)){
    if(array_key_exists('logout',$_POST)){
        session_unset();
        header("Location:login.php");
    }elseif(array_key_exists('bye',$_POST)){
        $dbh=dbConnect();
        $stmt = $dbh->prepare('UPDATE users SET delete_flg = 1 WHERE id = :us_id');
        $stmt-> execute(array(':us_id'=> $_SESSION['user_id']));
        session_destroy();
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
    <h1>マイページ</h1>
    <form action="" method = "post">
        <input type="submit" name="logout" value="ログアウト">
        <input type="submit" name ="bye" value="退会する">
        <a href="profEdit.php">プロフィール編集</a>

    </form>
</body>
</html>