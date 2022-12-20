<?php

require('../function/function.php');

debug('/////////商品詳細ページ//////////');

//商品IDをgetパラメーターに
$p_id = (!empty($_GET['p_id']))? $_GET['p_id'] :'';
$viewData= getProductOne($p_id);

var_dump($viewData);

if(empty($viewData)){
    error_log('エラー：指定ページに不正な値が入りました');
    header("Location:index.php");
}
debug('DBデータ：'.print_r($viewData,true));
//POST送信されていた場合
if(!empty($_POST['submit'])){
    debug('POST送信があります。');
    require('../function/auth.php');

    try{
      $dbh = dbConnect();
      $sql = 'INSERT INTO bord (sale_user, buy_user, product_id, create_date) VALUES (:s_uid, :b_uid, :p_id, :date)';
      $data = array (':s_uid'=>$viewData['user_id'],':b_uid'=>$_SESSION['user_id'],':p_id'=>$p_id,':date'=> date('Y-m-d H:i:s'));
      $stmt = queryPost($dbh,$sql,$data);
      if($stmt){
        $_SESSION['msg_success']=SUC05;
        debug('連絡掲示板へ遷移します');
        header("Location:msg.php?m_id=".$dbh->lastInsertID());
          }
        }catch(Exception $e){
          error_log('エラー発生：'.$e->getMessage());
          $err_msg['common']=ERR04;
        }
      }
      debug('///////画面表示処理終了///////')
      ?>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <style>
      .badge{
        padding: 5px 10px;
        color: white;
        background: #7acee6;
        margin-right: 10px;
        font-size: 16px;
        vertical-align: middle;
        position: relative;
        top: -4px;
      }
      #main .title{
        font-size: 28px;
        padding: 10px 0;
      }
      .product-img-container{
        overflow: hidden;
      }
      .product-img-container img{
        width: 100%;
      }
      .product-img-container .img-main{
        width: 750px;
        float: left;
        padding-right: 15px;
        box-sizing: border-box;
      }
      .product-img-container .img-sub{
        width: 230px;
        float: left;
        background: #f6f5f4;
        padding: 15px;
        box-sizing: border-box;
      }
      .product-img-container .img-sub:hover{
        cursor: pointer;
      }
      .product-img-container .img-sub img{
        margin-bottom: 15px;
      }
      .product-img-container .img-sub img:last-child{
        margin-bottom: 0;
      }
      .product-detail{
        background: #f6f5f4;
        padding: 15px;
        margin-top: 15px;
        min-height: 150px;
      }
      .product-buy{
        overflow: hidden;
        margin-top: 15px;
        margin-bottom: 50px;
        height: 50px;
        line-height: 50px;
      }
      .product-buy .item-left{
        float: left;
      }
      .product-buy .item-right{
        float: right;
      }
      .product-buy .price{
        font-size: 32px;
        margin-right: 30px;
      }
      .product-buy .btn{
        border: none;
        font-size: 18px;
        padding: 10px 30px;
      }
      .product-buy .btn:hover{
        cursor: pointer;
      }
      </style>
</head>
<body>
  <section id="main">
    <div class="title">
      <span class="badge"><?php echo sanitize($viewData['category']); ?></span>
      <?php echo sanitize($viewData['name']); ?>
    </div>
    <div class="product-img-container">
            <div class="img-main">
                <img src="<?php echo sanitize($viewData['pic1']); ?>" alt="メイン画像：
                <?php echo sanitize($viewData['name']); ?>" id="js-switch-img-main">
            </div>
            <div class="img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="画像１：<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="画像２：<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="画像３：<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
            </div>
        </div>
        <div class="product-detail">
            <p><?php echo sanitize($viewData['comment']); ?></p>
        </div>
        <div class="product-buy">
            <div class="item-left">
                <a href="index.php<?php appendGetParam(array('p_id')) ;?>">&lt;商品一覧に戻る</a>
            </div>
            <form action="" method="post">
                <div class="item-right">
                    <input type="submit" value="買う" name="submit" class="btn btn-primary" style="margin-top:0;">
                </div>
            </form>
        <div class="item-right">
                <p class="price">¥<?php echo sanitize(number_format($viewData['price'])); ?></p>
        </div>
    </div>
    </section>
    <?php require('footer.php');?>




</body>
</html>