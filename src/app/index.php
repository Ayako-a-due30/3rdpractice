<?php

require('../function/function.php');
require('../function/auth.php');

$currentPageNum = (!empty($_GET['p']))?$_GET['p']:1;
// if(!is_int(int)$currentPageNum)
// {
//     header("Location:index.php");
// }
//表示件数
$listSpan=3;
$currentMinNum = (($currentPageNum-1)*$listSpan);
// $dbProductData = getProductList($currentMinNum);
$dbCategoryData = getCategory();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事一覧</title>
</head>
<body>
    <?php echo $dbProductData;?>
    <section id= "main">

    </section>
    
</body>
</html>