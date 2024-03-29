<?php
//セッション

//ログ
ini_set('log_errors','on');
ini_set('error_log','php.log');
$debug_flg = true;
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}
session_save_path("/var/tmp");
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
const ERR11 = 'メールアドレスかパスキーに誤りがあるようです';
const ERR12 = 'パスワードが一致しません';
const ERR13 = 'メールを送信できませんでした';
const SUC01 = 'プロフィール更新しました';
const SUC02 = 'パスワードを更新しました';
const SUC03 ='登録済みのメールアドレスにキーを送ります。';
const SUC04 = '登録しました';
const SUC05 ='連絡画面に遷移します';

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
        debug('クエリに失敗しました。');
        debug('失敗したSQL：'.print_r($stmt,true));
            return 0;
    }
    return $stmt;
}
function debugLogStart(){
    debug('///画面表示処理開始/////');
    debug('セッションID：'.session_id());
    debug('セッション変数の中身：'.time());
    debug('現在日時タイムスタンプ：'.time());
    if(!empty($_SESSION['login_date']&&!empty($_SESSION['login_limit']))){
        debug('ログイン期限日時：'.($_SESSION['login_date']+$_SESSION['login_limit']));
    }
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
//パスワード：6文字以上255文字以内、半角
function validPass($str, $key ,$max = 255, $min=6,){
    validMaxLength();
    validMinLength();
    validHalf();
}
//ユーザー情報取得
function getUser($u_id){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT*FROM users WHERE id = :u_id AND delete_flg=0';
        $data = array(':u_id' => $u_id);

        $stmt = queryPost($dbh, $sql,$data);
        if($stmt){
            return $stmt->fetch (PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }catch(Exception $e){
        global $err_msg;
        $err_msg['common']=ERR04;
    }
}

function getFormData($str,$flg=false){
    if($flg){
        $method =$_GET;
    }else{
        $method = $_POST;//基本POST
    }
    global $dbFormData;
    if(!empty($dbFormData)){//ユーザーデータが入ってて
        if(!empty($err_msg[$str])){//フォームから送信されたエラーメッセージが入ってたらこっち
            if(isset($method[$str])){//フォームから送信されたデータがあったら
                return sanitize($method[$str]);//送信されたデータを返す
            }else{//フォームから送信されたデータがなかったら
                return sanitize($dbFormData[$str]);//（フォームから送信されてないのにエラーメッセージが出るって、考えられる？）登録済みデータを返す
            }
        }else{
            if(isset($method[$str]) && $method[$str]!==$dbFormData[$str]){
                return sanitize($method[$str]);//送信されたデータと登録データが違ったら送信された方を返す
            }else{
                return sanitize($dbFormData[$str]);//同じなら登録データを返す
            }
        }
    }else{//登録済みデータがなかったら、送信データを返す
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}

//認証キー作成
function makeRandKey(){
    $str ='';
    $str = mt_rand(10000,99999);
    return $str;
}

//メール送信
function sendMail($from,$to,$subject,$comment){
    if(!empty($to)&& !empty($subject)&& !empty($comment)){
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $result= mb_send_mail($to,$subject,$comment,"From:".$from);
        if($result){
            $_SESSION['message']=SUC03;
        }else{
            $_SESSION['message']= ERR13;
        }
    }
}

function getProduct($u_id,$p_id){
    try{
        $dbh=dbConnect();
        $sql= 'SELECT*FROM product WHERE user_id =:u_id AND id =:p_id AND delete_flg=0';
        $data= array(':u_id'=>$u_id,':p_id'=>$p_id);
        $stmt=queryPost($dbh,$sql,$data);
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }catch(Exception $e){
        global $err_msg;
        $err_msg = ERR04;
    }
}

function getProductData($u_id,$p_id){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT id FROM product WHERE $u_id = :u_id AND delete_flg= 0';
        $data= array(':u_id' => $u_id);

        
    }catch (Exception $e){
        $err_msg['common']= ERR04;
    }
}
function getProductList($category,$sort,$currentMinNum=1,$span = 20){
    debug('商品情報を取得');
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // 件数用のSQL文作成
        $sql = 'SELECT id FROM product';
        if(!empty($category))$sql .=' WHERE category_id = '.$category;
        if(!empty($sort)){
            switch($sort){
                case 1:
                    $sql .= ' ORDER BY price ASC';
                    break;
                case 2:
                    $sql .= ' ORDER BY price DESC';
                    break;
            }
        }
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst['total'] = $stmt->rowCount(); //総レコード数
        $rst['total_page'] = ceil($rst['total']/$span); //総ページ数
        if(!$stmt){
            return false;
            }  
        // ページング用のSQL文作成
        $sql = 'SELECT * FROM product';
            if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
            if(!empty($sort)){
            switch($sort){
                case 1:
                $sql .= ' ORDER BY price ASC';
                break;
                case 2:
                    $sql .= ' ORDER BY price DESC';
                break;
            }
        } 
      $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;

      $data = array();
      debug('SQL：'.$sql);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
  
      if($stmt){
        // クエリ結果のデータを全レコードを格納
        $rst['data'] = $stmt->fetchAll();
        return $rst;
      }else{
        return false;
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
  }

function getCategory(){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT*FROM category';
        $data = array();
        $stmt = queryPost($dbh,$sql,$data);
        if($stmt){
            return $stmt ->fetchAll();
        }else{
            return false;
        }
    }catch(Exception $e){
        global $err_msg;
        $err_msg = ERR04;
    }
}

function uploadImg($file,$key){
    if(isset($file['error']) && is_int($file['error'])){
        try{
            switch($file['error']){
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('ファイルが選択されていません');  
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default:
                throw new RuntimeException('その他のエラーが発生しました');
            }
            $type=@exif_imagetype($file['tmp_name']);
            
            if(!in_array($type,[IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG],true)){

                throw new RuntimeException('画像形式が未対応です');
            }
            
            
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);

            if (!move_uploaded_file($file['tmp_name'], $path)){
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            chmod($path,0644);
            debug('ファイルは正常にアップロードされました');
            debug('ファイルパス：'.$path);
            return $path;
        } catch (RuntimeException $e){
            debug ($e->getMessage());
            global $err_msg;
            $err_msg[$key] =$e->getMessage();
        }
    }
}
function showImg($path){
    if(empty($path)){
            return 'img/sample-img.png';
        }else{
            return $path;
        }
    }
function appendGetParam($arr_del_key){
    if(!empty($_GET)){
        $str='?';
        foreach($_GET as $key =>$val){
            if(!in_array($key,$arr_del_key,true)){
                $str.=$key.'='.$val.'&';
            }
        }
        $str = mb_substr($str,0,-1,"UTF-8");
        echo $str;
    }
}
function pagination($currentPageNum,$totalPageNum,$link='',$pageColNum = 5){
    if($currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
        //インデックスは今のページからマイナス４の値から始まって
        $minPageNum = $currentPageNum-4;
        //今のページが最終インデックス
        $maxPageNum = $currentPageNum;
        //今のページが最終ページから１ページ前で５ページ以上あったら
      } elseif($currentPageNum == ($totalPageNum -1) && $totalPageNum >=$pageColNum){
        //最初のインデックスは３ページ前から始まって
        $minPageNum = $currentPageNum-3;
        //最後のインデックスは１つ後のページで終了
        $maxPageNum = $currentPageNum+1;
        //今２ページ目で全ページ数が５ページ以上あったら
      } elseif($currentPageNum ==2 && $totalPageNum >= $pageColNum){
        //最初のインデックスは１つ前のページから始まって
        $minPageNum = $currentPageNum -1;
        //最後のインデックスは３つあとが最終
        $maxPageNum = $currentPageNum +3;
        //１ページ目だったら
      } elseif($currentPageNum ==1 && $totalPageNum >=$pageColNum){
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
        //全ページが５ページなかったら
      } elseif($totalPageNum < $pageColNum){
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
        //それ以外だったらインデックスは２ページ前から始まって、２ページ後から始まる
      } else{
        $minPageNum = $currentPageNum -2;
        $maxPageNum = $currentPageNum +2;
      }
      echo '<div class="pagination">';
      echo '<ul class="pagination-list">';
        if($currentPageNum != 1){
          echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
          echo '<li class="list-item ';
          if($currentPageNum == $i ){ echo 'active'; }
          echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
        }
        if($currentPageNum != $maxPageNum){
          echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
        }
      echo '</ul>';
    echo '</div>';
  
}

function getProductOne($p_id){
    debug('商品情報を取得：商品ID'.$p_id);
    try{
        $dbh=dbConnect();
        $sql='SELECT 
        p.id,p.name,p.comment,p.price,p.pic1,p.pic2,p.pic3,p.user_id,p.create_date,p.update_date,c.name 
        AS category
        FROM product 
        AS p 
        LEFT JOIN category 
        AS c 
        ON p.category_id=c.id 
        WHERE p.id=:p_id 
        AND p.delete_flg = 0 
        AND c.delete_flg =0';
        $data = array(':p_id' =>$p_id);
        $stmt =queryPost($dbh,$sql,$data);
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log('エラー：'.$e->getMessage());
    }
}

//お気に入り登録
function registLike($u_id, $p_id){
    debug('お気に入り情報を登録します');
    debug('ユーザーID：'.$u_id);
    debug('商品ID：'.$p_id);

    try{
        $dbh = dbConnect();
        $sql = 'INSERT INTO `like`(product_id, user_id) VALUES(:product_id,:user_id)';
        $data = array(':u_id'=>$u_id,':p_id'=>$p_id);

        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            debug('お気に入り登録しました');
            return $stmt;
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}
function isLike($u_id,$p_id){
    debug('お気に入り情報があるか確認します');
    debug('ユーザーID：'.$u_id);
    debug('商品ID:'.$p_id);

    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM `like` 
        WHERE product_id = :p_id AND user_id = :u_id';
        $data = array(':u_id'=>$u_id,':p_id'=>$p_id);

        $stmt = queryPost($dbh, $sql, $data);
        if($stmt->rowCount()){
            debug('お気に入りです');
            return true;
        }else{
            debug('お気に入りではありません');
            return false;
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}

//お気に入りをマイページに表示
function showLike($u_id){
    debug('お気に入りをマイページに表示します');
    try{
        $dbh = dbConnect();
        $sql = 'SELECT l.product_id, l.user_id, l.create_date, p.name, p.price, p.pic1 
                FROM `like` as l
                inner join product as p
                on l.product_id = p.id
                where l.user_id = :id';
        $data = array(':id' => $u_id);
        $stmt = queryPost($dbh, $sql,$data);
        if($stmt){
            return $stmt ->fetchAll();
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log('エラー'.$e->getMessage());
    }
}


//ログイン認証

function isLogin(){
    if(!empty($_SESSION['login_date'])){
        debug('ログイン済みユーザーです');

        if(($_SESSION['login_date']+ $_SESSION['login_limit'])<time()){
            debug('ログイン有効期限オーバーです');

            session_destroy();
            return false;
        }else{
            debug('ログイン有効期限内です');
            return true;
        }

    }else{
        debug('未ログインユーザーです');
        return false;
    }
}
function getMsgsAndBord($id){
    debug('msg情報を取得：掲示板ID'.$id);
    try{
        $dbh = dbConnect();

        $sql = 'SELECT  b.id, b.sale_user, b.buy_user, b.product_id,b.create_date,m.id,m.send_date,m.to_user, m.from_user, m.msg 
                FROM bord AS b 
                Left JOIN message AS m 
                ON b.id = m.bord_id 
                WHERE b.id = :id
                ORDER BY m.send_date ASC';
                                
        $data = array(':id'=>$id);
        $stmt = queryPost($dbh, $sql,$data);
        if($stmt){
            return $stmt->fetchAll();
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log('エラー'.$e->getMessage());
    }
}


//一回だけ表示
function getSessionFlash($key){
    if(!empty($_SESSION[$key])){
        $data= $_SESSION[$key];
        $SESSION[$key]='';
        return $data;
    }
}
//掲示板

//サニタイズーーーーーーーーーーーーーーーーー
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}
