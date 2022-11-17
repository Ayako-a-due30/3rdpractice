<?php
error_reporting(E_ALL);
ini_set('display_errors','on');

require('../function/function.php');
$dbh = dbConnect();
if(!empty($_POST)){
    $email = $_POST['email'];
    $pass= $_POST['pass'];
    $pass2 = $_POST['pass2'];
//未入力チェック
    validRequired($email,'email');
    validRequired($pass,'pass');

    
    if(empty($err_msg)){

        //メール形式
        validEmail($email,'email');
        //メール重複
        DupEmail($email);
        //パスワード再入力と一致しているか
        validMatch($pass, $pass2, 'pass');
        //パスワード6文字以上か
        validMinLength($pass,'pass');
        //パスワード２５５文字以内か
        validMaxLength($pass,'pass');
        

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
        $stmt->execute(array(':email'=>$email,':password'=>password_hash($pass,PASSWORD_DEFAULT),':create_date'=>date('Y-m-d H:i:s')));
        $_SESSION['user_id']=$dbh->lastInsertId();
        header("Location:mypage3.php");

    }



}
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
    <form action="" method="post">
        <span>
            <?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </span>
        <span>
            <?php if(!empty($err_msg['email'])) echo $err_msg['email'];?>
        </span><br>
        E-mail<input type="email" name="email" value="<?php if(!empty($err_msg['email'])) echo $_POST['email'];?>"><br>
        <span>
            <?php if(!empty($err_msg['pass'])) echo $err_msg['pass'];
            ?>
        </span>
        <br>
        パスワード(半角英数６文字以上２５５文字以内)<input type="password" name="pass">
        <br>
        パスワード（再入力）<input type="password" name="pass2"><br>
            <input type="submit" value="送信">

    </form>
    
</body>
</html>