<?php

require('../function/function.php');
require('../function/auth.php');

///==============画面表示=====================-====---
$dbFormData = getUser($_SESSION['user_id']);

$dbh = dbConnect();
if(!empty($_POST)){
    debug('POST送信あり：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));
    $username = $_POST['username'];
    $age = $_POST['age'];
    $tel = $_POST['tel'];
    $zip = $_POST['zip']?$_POST['zip']:0;
    $addr = $_POST['addr'];
    $email = $_POST['email'];
    $pic =(!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic'):'';
    // 入力チェック
    // validRequired($username,'username');
    // validRequired($age,'age');
    // validRequired($tel,'tel');
    // validRequired($zip,'zip');
    // validRequired($addr,'addr');
    // validRequired($email,'email');

    if(empty($err_msg)){
        //半角チェック
        validHalf($age,'age');
        validHalf($tel,'tel');
        //電話番号形式
        validTel($tel,'tel');
        //郵便番号形式
        validZip($zip,'zip');
        //Email形式チェック
        validEmail($email,'email');

        if(empty($err_msg)){
            try{
                $dbh= dbConnect();
                $sql = 'UPDATE users SET username =:username,age=:age,tel=:tel,zip=:zip,addr=:addr,email =:email,pic =:pic WHERE id =:u_id';
                $data = array(':username'=>$username,':age'=>$age,':tel'=>$tel,':zip'=>$zip,':addr'=>$addr,':email'=>$email,':pic' =>$pic,':u_id'=>$_SESSION['user_id']);
                $stmt= queryPost($dbh,$sql,$data);
                    if($stmt){
                        $_SESSION['success']=SUC01;
                        header("Location:mypage3.php");
                    }
            }catch(Exception $e){
                $err_msg['common']= ERR04;
            }
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
    <style>
        input{
            margin:10px;
        }
    </style>
</head>
<body>
    <h2>プロフィール編集画面</h2>
    <form action="" method="post" enctype="multipart/form-data">
            <?php if (!empty($err_msg['common'])) echo $err_msg['common'];?>
            <?php if(!empty($err_msg['username'])) echo $err_msg['username'];?>
        お名前*<input type="text" name="username" value = <?php echo getFormData('username'); ?>><br>
            <?php if(!empty($err_msg['age'])) echo $err_msg['age'];?>
        年齢*<input type="text" name="age" value =<?php echo getFormData('age');?>><br>
            <?php if(!empty($err_msg['tel'])) echo $err_msg['tel']; ?>
        電話番号(ハイフンなし)*<input type="tel" name="tel" value=<?php echo getFormData('tel')?>><br>
            <?php if(!empty($err_msg['zip'])) echo $err_msg['zip'];?>
        郵便番号*<input type="text" name="zip" value=<?php echo getFormData('zip');?>><br>
            <?php if(!empty($err_msg['addr'])) echo $err_msg['addr'];?>
        住所*<input type="text" name="addr" value=<?php echo getFormData('addr'); ?>><br>
        メールアドレス<input type="email" name = "email" value=<?php echo getFormData('email');?>><br>
        <?php if(!empty($err_msg['email'])) echo $err_msg['email'];?>
        登録画像 <input type="file" name="pic" value=<?php echo getFormData('pic'); ?>><br>
        <?php if(!empty($err_msg['pic'])) echo $err_msg['pic']; ?>
        <input type="submit" value="更新する"><br>

    </form>
    <a href="mypage3.php">マイページへ</a>
</body>
</html>