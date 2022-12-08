<footer>
    フッター部分
</footer>
<script src="../function/jquery-3.6.1.min.js"></script>
<script>
$(function(){
    var $ftr = $('#footer');
    // if(window.innerHeight > $ftr.offset().top+ $ftr.outerHeight()){
    //     $ftr.attr({'style':'position:fixed;top:'+ (window.innerHeight-$ftr.outerHeight())+ 'px;'});

    // }
    var $jsShowMsg= $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s]+|[\s]+$/g,"").length){
        $jsShowMsg.slideToggle('slow');
        setTimeout(function(){
            $jsShowMsg.slideToggle('slow');
        },5000);
    }
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');
    $dropArea.on('dragover',function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).css('border','3px #ccc dashed');
    });
    $dropArea.on('dragleave',function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).css('border','none');
    })
    $fileInput.on('change',function(e){
        $dropArea.css('border','none');
        var file = this.files[0],
        $img=$(this).siblings('.prev-img'),
        fileReader=new FileReader();
        fileReader.onload= function(event){
            $img.attr('src',event.target.result).show();
        };
        fileReader.readAsDataURL(file);
    });
    var $countUp= $('#js-count'),
    $countView=$('#js-count-view');
    $countUp.on('keyup',function(e){
        $countView.html($(this).val().length);
    });
    var $switchImgSubs=$('.js-switch-img-sub'),
    $switchImgMain=$('#js-switch-img-main');
    $switchImgSubs.on('click',function(e){
        $switchImgMain.attr('src',$(this).attr('src'));
    });
});
</script>