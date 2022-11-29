<?php
require('../function/function.php');
$result='';
if(!empty($_POST)){
    $passKey = (int)$_POST['passKey'];
    $keepKey= $_SESSION['auth_key'];
    validRequired($passKey,'passKey');

    if($passKey === $keepKey){
        try{
            $dbh = dbConnect();
            $sql = 'SELECT*FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $_SESSION['auth_email']);
            $stmt = queryPost($dbh,$sql,$data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
            if($result){
                $_SESSION['user_id']= $result['id'];
                $_SESSION['login_date'] = time();
                $_SESSION['login_limit'] = 60*60;
                header("Location:changePass.php");

            }else{
                $err_msg['passKey']= ERR04;
            }
        }catch(Exception $e){
            $err_msg['passKey']= ERR04;
        }
    }else{
        $err_msg['passKey']= ERR11;
    }
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>パスコード入力</title>
        <h1>パスコード入力</h1>
        
    <!-- <?php print_r($_SESSION); ?><br><br> -->
    <!-- <?php print_r($_POST);?><br><br> -->
    <?php var_dump ($passKey); ?><br><br>
    <?php var_dump ($_SESSION['auth_key']); ?><br>

    <form action="" method ="post">
        <?php if(!empty($err_msg['passKey'])) echo $err_msg['passKey']; ?>
        <input type="text" name="passKey">
        <input type="submit" value="送信する">
    </form>
</head>
<body>
    
</body>
</html>