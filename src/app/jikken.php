<?php


echo $array[1][0];

$viewData

array(1) { 
    [0]=> array(9) { 
        ["id"]=> string(2) "57" 
        ["sale_user"]=> string(2) "40" 
        ["buy_user"]=> string(2) "40" 
        ["product_id"]=> string(1) "1" 
        ["create_date"]=> string(19) "2022-12-27 14:08:08" 
        ["send_date"]=> string(19) "2022-12-14 11:48:12" 
        ["to_user"]=> string(2) "43" 
        ["from_user"]=> string(2) "40" 
        ["msg"]=> string(9) "kudamono-" } 
    }
?>
    <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?></p>
    <div id="contents" class="site-width">
      <?php if(!empty($err_msg['common']))echo $err_msg['common']; ?>
        <section id="main">
            <div class="msg-info">
                <div class="avatar-img">
                    <img src="<?php echo showImg(sanitize($partnerUserInfo['pic'])); ?>" alt="" class="avatar"><br>
                </div>
                <div class="avatar-info">
                    <?php echo sanitize($partnerUserInfo['username']).''.sanitize($partnerUserInfo['age']).'歳'; ?><br>
                    〒<?php echo wordwrap($partnerUserInfo['zip'],4,"-",true); ?>
                    <?php echo sanitize($partnerUserInfo['addr']);  ?><br>
                    TEL:<?php  echo sanitize($partnerUserInfo['tel']);?>
                </div>
                <div class="product-info">
                    <div class="left">
                        取引商品<br>
                        <img src="<?php echo showImg(sanitize($productInfo['pic1'])); ?>" alt="" height="70px" width="auto">
                    </div>
                    <div class="right">
                        <?php echo sanitize($productInfo['name']); ?><br>
                        取引金額：<span class="price">¥<?php echo number_format(sanitize($productInfo['price'])); ?></span><br>
                        取引開始日：<?php echo date('Y/m/d',strtotime($viewData[0]['create_date'])); ?>
                    </div>
                </div>
            </div>
            <div class="area-bord" id="js-scroll-bottom">
                <?php 
                var_dump($viewData);
                    if(isset($viewData["msg"])){
                        foreach($viewData as $key => $val){
                        if(!empty($val['from_user']) && $val['from_user']==$partnerUserId){ ?>
                        <div class="msg-cnt msg-left">
                            <div class="avatar">
                                <img src="<?php echo showImg(sanitize($partnerUserInfo['pic'])); ?>" alt="" class="avatar"><br>
    
                            </div>
                            <p class="msg-inrTxt">
                                <span class="triangle"></span>
                                <?php echo sanitize($val['msg']); ?>
                            </p>
                            <div style="font-size:.5em;"><?php echo sanitize($val['send_date']); ?></div>
                        </div>
                    <?php
                    // }else{
                    //     ?>
                    //     <div class="msg-cnt msg-right">
                    //         <div class="avatar">
                    //             <img src="<?php echo sanitize(showImg($myUserInfo['pic'])); ?>" alt="" class="avatar">
                    //         </div>
                    //         <p class="msg-inrTxt">
                    //             <span class="triangle"></span>
                    //             <?php echo sanitize($val['msg']); ?>
                    //         </p>
                    //         <div style="font-size:.5em;text-align:right;"><?php echo sanitize($val['send_date']); ?></div>
                    //     </div>
                    //     <?php
                    //         }
                        }
                    }
                    }else{
                    ?>
                    <p style="text-align:center;line-height:20;">メッセージ投稿はまだありません</p>
                    <?php
                        }
                    ?>
            </div>
            <div class="area-send-msg">
                <form action="" method="post">
                    <textarea name="msg" id="" cols="30" rows="10"></textarea>
                    <input type="submit" value="送信" class="btn btn-send">
                </form>
            </div>
        </section>
    </div>
    