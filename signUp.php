<?php
error_reporting(E_ALL);
ini_set('display_errors','on');

require('function.php');
$dbh = dbConnect();
session_start();
if(!empty($_POST)){
    $email = $_POST['email'];
    $pass= $_POST['pass'];

    $dsn = 'mysql:dbname=freamarket;host=localhost;charset=utf8';
    $user='root';
    $password='root';
    $options = array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,
    );
    $dbh=new PDO($dsn,$user,$password,$options);
    $stmt = $dbh->prepare('INSERT INTO users (email,password,create_date) VALUES(:email,:password,:create_date)');
    $stmt->execute(array(':email'=>$email,':password'=>$pass,':create_date'=>date('Y-m-d H:i:s')));
    $_SESSION['user_id']=$dbh->lastInsertId();
    header("Location:mypage3.php");


}
    //DBに登録
//     try{
//         $dbh= dbConnect();
//         $sql = 'INSERT INTO users (email,password,login_time,update_date) VALUES (:email,:password,:login_time,:update_date)';
//         $data = array(
//             ':email'=>$email,
//             ':password' =>$pass,
//             ':login_time'=> date('Y-m-d H:i:s'),
//             ':update_date'=> date('Y-m-d H:i:s')); 
//             $stmt = queryPost($dbh,$sql,$data);
//             if ($stmt){
//                 $sesLimit = 60*60;
//                 $_SESSION['login_date']=time();
//                 $_SESSION['login_limit']=$sesLimit;
//                 $_SESSION['user_id']= $dbh->lastInsertId();
//                 header("Location:mypage3.php");
//             }else{
//                 echo '失敗1';
//             }
//     }catch(Exception $e){
//         echo '失敗2';
//     }


// }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>登録フォーム</h1>
    <form action=""method="post">
        E-mail<input type="email" name="email">
        パスワード<input type="password" name="pass">
        <input type="submit" value="送信">

    </form>
    
</body>
</html>