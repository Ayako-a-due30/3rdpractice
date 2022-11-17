<?php
require('../function/function.php');
    $dbh = dbConnect();
    $sesLimit = 60*60;
    $user_id =$_SESSION['user_id'];
    print_r($_SESSION);
if(!empty($_POST)){
    if(array_key_exists('logout',$_POST)){
        session_unset();
        header("Location:./login.php");
    }elseif(array_key_exists('bye',$_POST)){
        $dbh=dbConnect();
        $stmt = $dbh->prepare('UPDATE users SET delete_flg = 1 WHERE id = :us_id');
        $stmt-> execute(array(':us_id'=> $_SESSION['user_id']));
        session_destroy();
        header("Location:./login.php");
    }
}
$dbFormData = getUser($_SESSION['user_id']);
if(!empty($_POST)){
    $username = $_POST['username'];
    $age = $_POST['age'];
    $tel = $_POST['tel'];
    $zip = $_POST['zip'];
    $addr = $_POST['addr'];
    $email = $_POST['email'];
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
    こんにちは、<?php echo getFormData('username'); ?>さん！
    <form action="" method = "post">
        <input type="submit" name="logout" value="ログアウト">
        <input type="submit" name ="bye" value="退会する">
        <a href="./profEdit.php">プロフィール編集</a>

    </form>
</body>
</html>