<?php
session_start();

if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $dsn = 'mysql:dbname=freamarket;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,
    );
    $dbh=new PDO($dsn,$user,$password,$options);
    $stmt =$dbh->prepare('SELECT*FROM users WHERE email= :email AND password=:password');
    $stmt->execute(array(':email'=>$email,':password'=>$pass));
    $result = 0;
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($result)){
            $_SESSION['login_date']=time();
            $_SESSION['user_id']=$dbh->lastInsertId();
            print_r($dbh);
            // header("Location:mypage3.php");
    }
}

//     $dbh = dbConnect();

//     $sql = ('SELECT*FROM users WHERE email = :email AND password = :password');
//     $data = array(':email'=>$email,':password'=>$pass);
//     $stmt = queryPost($dbh,$sql,$data);

//     $result = 0;
//     $result= $stmt->fetch(PDO::FETCH_ASSOC);
//     if (!empty($result)){
//         header("Location.mypage3.php");
//     }

// }   

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ログインページ</h1>
    <form action="" method="post">
        メールアドレス<input type="email"  name="email">
        パスワード<input type="password" name="pass">
        <input type="submit" value="ログインする">
    </form>
</body>
</html>