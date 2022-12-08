<?php
error_reporting(E_ALL);
ini_set('display_errors','on');

    require('../function/function.php');
    // require('../function/auth.php');

    $p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';//GETでp_idがあればそれを取ってきて、なければ空文字''
    $dbFormData = (!empty($p_id))? getProduct($_SESSION['user_id'],$p_id) :'';
    //↑$p_idが入ってたらgetProductでuser_idを取ってきて、なかったら空文字
    $edit_flg=(empty($dbFormData))?false:true;
    $dbCategoryData = getCategory();

    if(!empty($_POST)){
    $name = $_POST['name'];    
    $category = $_POST['category_id'];   
    $price = (!empty($_POST['price'])) ? $_POST['price']:0 ;
    $comment = $_POST['comment'];
    $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'],'pic1'):'';
    $pic1 = (empty($pic1) && !empty($dbFormData['pic1']) ? $dbFormData['pic1']: $pic1);
    $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'],'pic2'):'';
    $pic2 = (empty($pic2) && !empty($dbFormData['pic2']) ? $dbFormData['pic2']: $pic2);
    $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'],'pic3'):'';
    $pic3 = (empty($pic3) && !empty($dbFormData['pic3']) ? $dbFormData['pic3']: $pic3);

    try{
        $dbh = dbConnect();
        if($edit_flg){
            //更新する
            $sql = 'UPDATE product SET name=:name,category_id=:category,price=:price,comment=:comment,pic1=:pic1,pic2=:pic2,pic3=:pic3,user_id=:user_id AND id = :p_id';
            $data = array(':name'=>$name,':category'=>$category,':price'=>$price,':comment'=>$comment,':pic1'=>$pic1,':pic2'=>$pic2,':pic3'=>$pic3,':u_id'=>$_SESSION['user_id'],'p_id'=>$p_id);
        }else{
            //新規登録
            $sql = 'insert into product (name, category_id, price, comment, pic1, pic2, pic3, user_id, create_date ) values (:name, :category, :price, :comment,  :pic1, :pic2, :pic3, :u_id, :date)';
            $data = array(':name' => $name , ':category' => $category, ':price' => $price, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
        }
        $stmt= queryPost($dbh,$sql,$data);
        if($stmt){
            $_SESSION['msg_success']= SUC04;
            header("Location:mypage3.php");
        }
        }catch(Exception $e){
        $err_msg['common']= ERR04;
    }

    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>図面登録ページ</title>
</head>
<body>
    <h1><?php echo (!$edit_flg) ?'掲載する':'編集する'; ?></h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            商品名<br>
            <input type="text" name="name" value="<?php echo getFormData('name'); ?>"><br>
            カテゴリ<br>
            <select name="category_id" id="">
                <option value="0"<?php if(getFormData('category_id')===0){echo 'selected';} ?>>選択してください</option>
                <?php
                foreach($dbCategoryData as $key =>$val){
                    ?>
                <option value="<?php echo $val['id'] ?>"<?php if(getFormData('category_id')==$val['name']){echo 'selected';} ?>>
                    <?php echo $val['name']; ?>
            </option>
            <?php
                } 
            ?>
            </select>
            <br>
            名前<br>
            <input type="text" name="name"><br>
            <img src="<?php echo getFormData('pic1'); ?>" alt="" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">
            詳細<br>
            <textarea name="comment" id="" cols="30" rows="10"><?php echo getFormData('comment'); ?></textarea><br>
            金額<br>
            <input type="text" name="price" value="<?php echo (!empty(getFormData('price')))?getFormData('price'):0; ?>"><span class="option">円</span><br>
            <div class="dropContainer" style="background-color:#CCCCFF;margin:10px;">
                <label for="" class="area-drop">
                    画像１<br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" name="pic1" class="input-file" style="height:200px;">
                    <img src="<?php echo getFormData('pic1'); ?>" class="prev-img" alt="" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">
                    ドラッグ＆ドロップ<br>
                </label>
            </div>
            <div class="dropContainer" style="background-color:#CCCCFF; margin:10px;">
                <label for="" class="area-drop">
                    画像２<br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">

                    <input type="file" name="pic2" class="input-file" style="height:200px;">
                    <img src="<?php echo getFormData('pic2'); ?>" class="prev-img" alt="" style="<?php if(empty(getFormData('pic2'))) echo 'display:none;' ?>">
                    ドラッグ＆ドロップ                    
                </label>
            </div>
            <div class="dropContainer" style="background-color:#CCCCFF;margin:10px;">
                <label for="" class="area-drop">
                    画像３<br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" name="pic3" class="input-file" style="height:200px;">
                    <img src="<?php echo getFormData('pic3'); ?>" class="prev-img" alt="" style="<?php if(empty(getFormData('pic3'))) echo 'display:none;' ?>">
                    ドラッグ＆ドロップ
                </label>
            </div>

        </div>
        <input type="submit" value="<?php echo (!$edit_flg)?'登録する':'更新する' ?>">
    </form>
    <?php require('footer.php'); ?>
    </body>
</html>