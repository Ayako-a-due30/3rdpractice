<?php
require('../function/function.php');
require('../function/auth.php');

 if(!empty($_POST)){
    $nowPass = $_POST['nowPass'];
    $newPass = $_POST['newPass'];
    $newPassRe = $_POST['newPassRe'];

    validRequired($nowPass,'pass');
    validRequired($newPass,'pass');
    validRequired($newPassRe,'pass');

    $userData = getUser($_SESSION['user_id']);

    //今のパスワードは正しいか
    if(!password_verify($nowPass, $userData['password']))  {
        echo 'パスワード前のと違うよ';
    }
  
    //以下新しいパスワードについて-----------------
    //半角
        validHalf($newPass,'newPass');        
        //６文字以上
        validMinLength($newPass,'newPass');
        //255文字未満
        validMaxLength($newPass,'newPass');
        //再入力一致
        validMatch($newPass,$newPassRe,'newPass');

        if(empty($err_msg)){
            //書き換え
            try{
                $dbh = dbConnect();
                $sql ='UPDATE users SET password =:password WHERE id = :u_id';
                $data = array(':password'=>password_hash($newPass,PASSWORD_DEFAULT),':u_id'=> $_SESSION['user_id']);
                queryPost($dbh,$sql,$data);
                $_SESSION['success']=SUC01;
                header("Location:mypage3.php");
            }catch(Execute $e){
                $err_msg['common']= ERR04;
            }
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
    <h1>パスワード変更</h1>

    <form action=""method ="post">
        <?php if(!empty($err_msg['nowPass'])) echo $err_msg['nowPass']; ?><br>
        現在のパスワード<input type="password" name="nowPass"><br>
        <?php if(!empty($err_msg['newPass'])) echo $err_msg['newPass']; ?>
        新しいパスワード<input type="password" name="newPass"><br>
        新しいパスワード（再入力）<input type="password" name="newPassRe"><br>
        <input type="submit" value="送信">
    </form>
    <a href="mypage3.php">マイページへ</a>
    
</body>
</html>