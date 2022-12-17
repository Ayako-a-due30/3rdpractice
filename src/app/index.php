<?php

require('../function/function.php');
require('../function/auth.php');


debugLogStart();
debug('///トップページ////');

$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは１ページめ
$category = (!empty($_GET['c_id']))? $_GET['c_id']:'';
$sort = (!empty($_GET['sort']))? $_GET['sort']:'';
if(!is_int((int)$currentPageNum)){
    header("Location:index.php");
}
//表示件数
$listSpan=20;
$currentMinNum = (($currentPageNum-1)*$listSpan);
$dbProductData = getProductList($category,$sort,$currentMinNum);
$dbCategoryData = getCategory();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
      div .pagination{
        overflow:hidden;
      }
      li{
        list-style:none;
        float:left;
      }
      .pagination .list-item a{
        display:block;
        background-color:#eee;
        margin:20px;
        padding:20px;
        text-decoration:none;
      }
      .pagination .active a{
        display:block;
        background-color:#ff6666;
        margin:20px;
        padding:20px;
        text-decoration:none;
      }
      .panel-list{
        overflow:hidden;
      }
      .panel{
        float:left;
        margin:20px;
      }
    </style>
    <title>記事一覧</title>
</head>
<body>
    <section id= "sidebar">
      <form action="" method="get">
        <h1>カテゴリー</h1>
        <div class="selectbox">
          <span class="icn_select"></span>
          <select name="c_id" id="">
            <option value="0" <?php if(getFormData('c_id',true)==0){echo 'selected';} ?>>選択してください</option>
            <?php foreach($dbCategoryData as $key=>$val){ ?>
              <option value="<?php echo $val['id'] ?>"<?php if(getFormData('c_id',true) == $val['id']){echo 'selected';} ?>>
                <?php echo $val['name']; ?>
              </option>
              <?php } ?>
          </select>
            </div>
            <h1>表示順</h1>
            <div class="selectbox">
                <span class="icn_select"></span>
                <select name="sort" id="">
                  <option value="0" <?php if(getFormData('sort',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
                  <option value="1" <?php if(getFormData('sort',true) == 1 ){ echo 'selected'; } ?> >金額が安い順</option>
                  <option value="2" <?php if(getFormData('sort',true) == 2 ){ echo 'selected'; } ?> >金額が高い順</option>
                </select>
            </div>
            <input type="submit" value="検索">
        </form>
        <div class="search-title">
            <div class="search-left">
                <span class="total-num">
                    <?php 
                    echo sanitize($dbProductData['total']); ?>
                </span>件の商品が見つかりました。
            </div>
            <div class="search-right">
            <span class="num"><?php echo (!empty($dbProductData['data'])) ? $currentMinNum+1:0; ?></span> - 
            <span class="num"><?php echo ($currentMinNum + count($dbProductData['data'])); ?></span>件 / 
            <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
          </div>

            <div class="panel-list">
         <?php
            foreach($dbProductData["data"] as $key=>$val):
          ?>
            <a href="productDetail.php?p_id=<?php echo $val['id'].'&p='.$currentPageNum; ?>" class="panel">
              <div class="panel-head">
                <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['name']); ?>" style ="height:100px; width:100px;" >
              </div>
              <div class="panel-body">
                <p class="panel-title"><?php echo sanitize($val['name']); ?> <span class="price">¥<?php echo sanitize(number_format($val['price']),1); ?></span></p>
              </div>
            </a>
          <?php
            endforeach;
          ?>
        </div>
        <?php pagination($currentPageNum,$dbProductData['total_page']); ?>
        <a href="mypage3.php">マイページへ戻る</a>
    </section>
            $currentMinNum <br>
            <?php var_dump($currentMinNum); ?>
            <br>
            <br>$_GET <br>
            <?php var_dump($_GET); ?>
            <br>
            <br>$dbProductData['data'] <br>
            <?php var_dump($dbProductData['data']); ?>


    <?php require('footer.php'); ?>
</body>
</html>