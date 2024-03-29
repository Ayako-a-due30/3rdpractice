<?php
require('../function/function.php');

require('../function/auth.php');

    $dbh = dbConnect();
    $sesLimit = 60*60;
    $user_id =$_SESSION["user_id"];
    

    $showMyLike = showLike($_SESSION['user_id']);
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
    <title>マイページ</title>
</head>
<body>
    <h1>マイページ</h1>
    こんにちは、<?php echo getFormData('username'); ?>さん！
    <h2>お気に入り登録一覧</h2>


        <?php 
        foreach($showMyLike as $key =>$val):

        ?>
            <p><?php echo ($val["name"]); ?></p>
            <p><?php echo ($val["price"]); ?></p>
            <img src ="<?php echo ($val["pic1"]); ?>">
        <?php  
        endforeach;
        ?>
        



    <h2>連絡掲示板一覧</h2>
    <h2>登録商品一覧</h2>

    <form action="" method = "post">
        <input type="submit" name="logout" value="ログアウト">
        <input type="submit" name ="bye" value="退会する"><br>
        <a href="./profEdit.php">プロフィール編集</a><br>
        <a href="./passEdit.php">パスワード変更</a><br>
        <a href="./registArticle.php"> 記事登録</a><br>
        <a href="./index.php">記事一覧</a>



    </form>

</body>
</html>