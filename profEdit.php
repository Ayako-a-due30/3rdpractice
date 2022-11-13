<?php

require('function.php');

session_start();
$dbh = dbConnect();
var_dump($_SESSION);
if(!empty($_POST)){
    $username = $_POST['username'];
    $age = $_POST['age'];
    $tel = $_POST['tel'];
    $zip = $_POST['zip'];
    $addr = $_POST['addr'];

    $dsn = 'mysql:dbname=freamarket;host=localhost;charset=utf8';
    $user = 'root';
    $password='root';
    $options = array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,
    );
    $dbh = new PDO($dsn,$user,$password,$options);
    $stmt = $dbh->prepare('UPDATE users SET username =:username,age=:age,tel=:tel,zip=:zip,addr=:addr WHERE id =:u_id');
    $stmt->execute(array(':username'=>$username,':age'=>$age,':tel'=>$tel,':zip'=>$zip,':addr'=>$addr,':u_id'=>$_SESSION['user_id']));
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
    <h2>プロフィール編集画面</h2>
    <form action="" method="post">
        お名前<input type="text" name="username">
        年齢<input type="text" name="age">
        電話番号<input type="tel" name="tel">
        郵便番号<input type="number" name="zip">
        住所<input type="text" name="addr">
        <input type="submit" value="更新する">
    </form>

</body>
</html>