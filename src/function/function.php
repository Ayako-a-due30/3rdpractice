<?php
//セッション
session_start();
//エラーメッセージ格納用配列
$err_msg=array();
//定数エラーメッセージ
const ERR01 = '入力必須です';
const ERR02 ='メールアドレスの形式で入力してください';
const ERR03 ='登録済みのメールアドレスです。';
const ERR04 ='エラーが発生しました。しばらく経ってからやり直してください';
const ERR05 ='再入力と一致しません';
const ERR06='６文字以上で入力してください';
const ERR07='255文字以内で入力してください';
const ERR08 ='ハイフンなしの電話番号を入力してください';
const ERR09='半角英数字で入力してください';
const ERR10='郵便番号形式で入力してください';
const ERR11 = 'エラーが発生しました。しばらくしてからやり直してください';
const SUC01 = 'プロフィール更新しました';

function dbConnect(){
    $dsn = 'mysql:dbname=freamarket;host=localhost;charset=utf8';
    $user = 'root';
    $password='root';
    $options = array(
        PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,
    );
    $dbh = new PDO($dsn,$user,$password,$options);
    return $dbh;
}
function queryPost($dbh, $sql,$data){
    $stmt = $dbh->prepare($sql);
    if(!$stmt->execute($data)){
        $err_msg['common']= ERR04;
        return 0;
    }
    return $stmt;
}

//バリデーション
// 未入力チェック
function validRequired($str,$key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = ERR01;
    }
}
//Email形式
function validEmail($str,$key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$str)){
        global $err_msg;
        $err_msg[$key]= ERR02;
    }
}
//メール重複
function DupEmail($email){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*)FROM users WHERE email = :email AND delete_flg = 0';
        $data = array (':email' => $email);
        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt->fetch (PDO::FETCH_ASSOC);
        return $result;
        if(!empty(array_shift($result))){
            $err_msg['email']=ERR03;
        } 
    }catch(Exception $e){
        $err_msg ['common'] = ERR04;
    }
}
//同値チェック
function validMatch($str1,$str2,$key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = ERR05;
    }

}
//最小文字数
function validMinLength($str,$key,$min=6){
    global $err_msg;
    if(mb_strlen($str) < $min){
        $err_msg[$key] = ERR06;
    }
}
//最大文字数
function validMaxLength($str,$key,$max = 255){
    if(mb_strlen($str)>$max){
        global $err_msg;
        $err_msg['$key']=ERR07;
    }
}
//電話番号形式
function validTel($str,$key){
    global $err_msg;
    if(!preg_match('/^0[0-9]{9,10}\z/',$str)){
        $err_msg[$key]= ERR08;
    }
}
//郵便番号
function validZip($str,$key){
    if(!preg_match("/\A\d{3}-?\d{4}\z/",$str)){
        global $err_msg;
        $err_msg['zip']=ERR10;
    }
}
//半角英数字
function validHalf($str,$key){
    if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
        global $err_msg;
        $err_msg[$key]= ERR09;
    }
}


?>